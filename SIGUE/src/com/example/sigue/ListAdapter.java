package com.example.sigue;

import java.util.ArrayList;
import java.util.List;

import android.content.ClipData.Item;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class ListAdapter extends BaseAdapter {
	private Context context;
    private List<String> items;
    private ArrayList<Integer> identificadores;    
    public ListAdapter(Context context,List<String> items,ArrayList<Integer> id){
    	this.context = context;
    	this.items = items;
    	this.identificadores = id;
    }

	@Override
	public int getCount() {
		// TODO Auto-generated method stub
		return this.items.size();
		
	}

	@Override
	public String getItem(int position) {
		// TODO Auto-generated method stub
		return this.items.get(position);
		
	}

	@Override
	public long getItemId(int position) {
		// TODO Auto-generated method stub
		if(identificadores.size()>0){
		return identificadores.get(position);
		}else{
			return position;
		}
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		 View rowView = convertView;
		 
	        if (convertView == null) {
	            // Create a new view into the list.
	            LayoutInflater inflater = (LayoutInflater) context
	                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	            rowView = inflater.inflate(R.layout.list_item, parent, false);
	        }
	 
	        // Set data into the view.
	        TextView lvSubj = (TextView) rowView.findViewById(R.id.lblListItem);
	 
	        String item = this.getItem(position);
	        lvSubj.setText(item);
	 
	        return rowView;
	}

}
