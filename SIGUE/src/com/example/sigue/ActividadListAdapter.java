package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;

import org.json.JSONException;
import org.json.JSONObject;
import com.example.sigue.StatisticListAdapter.ViewHolder;
import com.example.sigue.library.DataBaseHandler;
import com.example.sigue.library.UserFunctions;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.app.AlertDialog.Builder;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.BaseExpandableListAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

public class ActividadListAdapter extends BaseExpandableListAdapter {
	private Context _context;
    private ArrayList<Actividad> _listDataHeader;
    private int posicion;
    private int user;
    Dialog customDialog = null;// header titles
    // child data in format of header title, child title
    //private HashMap<Actividad, String> _listDataChild;
	private UserFunctions userFunction;
	private static String KEY_SUCCESS = "success";

 
    public ActividadListAdapter(Context context, ArrayList<Actividad> listDataHeader,
            int usuario) {
        this._context = context;
        this._listDataHeader = listDataHeader;
        this.user = usuario;
        this.userFunction = new UserFunctions();
        
    }
 
    @Override
    public Object getChild(int groupPosition, int childPosititon) {
        return this._listDataHeader.get(groupPosition).getObservaciones();
    }
 
    @Override
    public long getChildId(int groupPosition, int childPosition) {
        return childPosition;
    }
 
    @Override
    public View getChildView(int groupPosition, final int childPosition,
            boolean isLastChild, View convertView, ViewGroup parent) {
 
        final String childText = (String) getChild(groupPosition, childPosition);
 
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) this._context
                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.list_item, null);
        }
 
        TextView txtListChild = (TextView) convertView.findViewById(R.id.lblListItem);
 
        txtListChild.setText(childText);
        return convertView;
    }
 
    @Override
    public int getChildrenCount(int groupPosition) {
        return 1;
    }
 
    @Override
    public Object getGroup(int groupPosition) {
        return this._listDataHeader.get(groupPosition);
    }
 
    @Override
    public int getGroupCount() {
        return this._listDataHeader.size();
    }
 
    @Override
    public long getGroupId(int groupPosition) {
        return groupPosition;
    }
 
    @Override
    public View getGroupView( int groupPosition, boolean isExpanded,
            View convertView, ViewGroup parent) {
        Actividad headerTitle = (Actividad) getGroup(groupPosition);
        ViewHolder holder;
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) this._context
                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.actividad_group, null);
        }
        
        holder = new ViewHolder();
 
        TextView nombre = (TextView) convertView.findViewById(R.id.nombreAct);
        nombre.setTypeface(null, Typeface.BOLD);
        nombre.setText("Nombre: " + headerTitle.getNombre());
        
        TextView peso = (TextView) convertView.findViewById(R.id.pesoAct);
        peso.setText("Peso: " + headerTitle.getPeso());
        
        TextView nota = (TextView) convertView.findViewById(R.id.notaAct);
        nota.setTypeface(null, Typeface.BOLD);
        nota.setText("Nota: "+ headerTitle.getNota());
        
        ImageView edit = (ImageView) convertView.findViewById(R.id.imageView1);
        edit.setFocusable(false);
        edit.setTag(groupPosition);
        edit.setOnClickListener(new OnClickListener(){

			@Override
			public void onClick(View arg0) {
				//Toast.makeText(arg0.getContext(), "Clicked Item Position :" + arg0.getTag(), Toast.LENGTH_SHORT).show();
				mostrar((Integer) arg0.getTag());
			}
        
        });
 
        return convertView;
    }
 
    @Override
    public boolean hasStableIds() {
        return false;
    }
 
    @Override
    public boolean isChildSelectable(int groupPosition, int childPosition) {
        return true;
    }
    
    public void updateData(){
    	
    }

	public void clear() {
		// TODO Auto-generated method stub
		_listDataHeader.clear();
		//_listDataChild.clear();
	}

	public void addAll(ArrayList<Actividad> listDataHeader) {
		// TODO Auto-generated method stub
		this._listDataHeader = listDataHeader;
        //this._listDataChild = listDataChild;
	}
	
	
    
	 public void mostrar(int pos)
	    {
		 posicion = pos;
	        // con este tema personalizado evitamos los bordes por defecto
	        customDialog = new Dialog(_context,R.style.Theme_Dialog_Translucent);
	        //deshabilitamos el título por defecto
	        customDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
	        //obligamos al usuario a pulsar los botones para cerrarlo
	        customDialog.setCancelable(false);
	        //establecemos el contenido de nuestro dialog
	        customDialog.setContentView(R.layout.dialog);
	         
	        TextView titulo = (TextView) customDialog.findViewById(R.id.titulo);
	        titulo.setText("Modificar");
	         
	        TextView nota = (TextView) customDialog.findViewById(R.id.nota);
	        nota.setText("Nota:");
	        TextView obs = (TextView) customDialog.findViewById(R.id.observaciones);
	        obs.setText("Observaciones::");
	        EditText edit1 = (EditText) customDialog.findViewById(R.id.et1);
	        edit1.setText(_listDataHeader.get(posicion).getNota());
	        EditText edit2 = (EditText) customDialog.findViewById(R.id.et2);
	        edit2.setText(_listDataHeader.get(posicion).getObservaciones());
	        
	        ((Button) customDialog.findViewById(R.id.aceptar)).setOnClickListener(new OnClickListener() {
	             
	            @Override
	            public void onClick(View view)
	            {
	                customDialog.dismiss();	                
	                String nota = ((EditText)customDialog.findViewById(R.id.et1)).getText().toString();
	                if(nota==_listDataHeader.get(posicion).getNota()){
	                	return;
	                }else{
	                	_listDataHeader.get(posicion).setNota(nota);
	                }
	                AlumnosActivity.modActividades(user,posicion,_listDataHeader.get(posicion));
	                
	                                
	                String obs = ((EditText)customDialog.findViewById(R.id.et2)).getText().toString();
	                if(obs==_listDataHeader.get(posicion).getObservaciones()){
	                	return;
	                }else{
	                	_listDataHeader.get(posicion).setObservaciones(obs);
	                }
	                AlumnosActivity.modActividades(user,posicion,_listDataHeader.get(posicion));
	                addAll(_listDataHeader);
	                notifyDataSetChanged();
	                
	                new Asincrono1().execute(userFunction);
	            }
	        });
	         
	        ((Button) customDialog.findViewById(R.id.cancelar)).setOnClickListener(new OnClickListener() {
	             
	            @Override
	            public void onClick(View view)
	            {
	                customDialog.dismiss();
	                Toast.makeText(_context, R.string.cancelar, Toast.LENGTH_SHORT).show();
	                 
	            }
	        });
	         
	        customDialog.show();
	    }  
	 
	 private class Asincrono1 extends AsyncTask<UserFunctions, Void, JSONObject> {
	    	
	        private final ProgressDialog dialog = new ProgressDialog(_context);
	        private int mode;
	        
	        protected void onPreExecute() {
	            this.dialog.setMessage("LOADING.................");
	            this.dialog.setOnCancelListener(new OnCancelListener() {
	                @Override
	                public void onCancel(DialogInterface dialog) {
	                    Asincrono1.this.cancel(true);
	                }
	            });
	            this.dialog.setCancelable(true);
	            this.dialog.show();
	        }
	        @Override
	    	protected JSONObject doInBackground(UserFunctions... userfunction) {
	        	JSONObject json = userFunction.modifyActivity(_listDataHeader.get(posicion).getNota(),
	        			_listDataHeader.get(posicion).getObservaciones(),_listDataHeader.get(posicion).getId());
	    		return json;
	    	}
	        

		@Override
	        protected void onPostExecute(JSONObject json) {
			 // check for login response
			if (this.dialog.isShowing()) {
	            this.dialog.dismiss();
	        }

	        try {

	            if (json.getString(KEY_SUCCESS) != null) {

	                String res = json.getString(KEY_SUCCESS);

	               
	                }	        

	        }catch (NullPointerException e) {
				// TODO Auto-generated catch block
				Toast.makeText(_context, "Sin Resultados",
			            Toast.LENGTH_SHORT).show();
				e.printStackTrace();
			} catch (JSONException e) {

	            e.printStackTrace();

	        }
		}
	 
		@Override
		protected void onCancelled() {
	        Toast.makeText(_context, "Tarea cancelada!",
	            Toast.LENGTH_SHORT).show();
	    }
	 }
}
