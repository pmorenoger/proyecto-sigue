package com.example.sigue;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

import com.androidplot.pie.PieChart;
import com.androidplot.pie.Segment;
import com.androidplot.pie.SegmentFormatter;
import com.androidplot.xy.LineAndPointFormatter;
import com.androidplot.xy.SimpleXYSeries;
import com.androidplot.xy.XYSeries;
import com.example.sigue.R.string;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnTouchListener;
import android.view.ViewGroup;
import android.view.animation.AccelerateInterpolator;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.widget.BaseExpandableListAdapter;
import android.widget.TextView;
import android.widget.ViewFlipper;

public class StatisticListAdapter extends BaseExpandableListAdapter {
 
    private Context _context;
    private List<String> _listDataHeader; // header titles
    // child data in format of header title, child title
    private HashMap<String, ArrayList<String>> _listDataChild;
    private PieChart mySimplePiePlot;
    public float init_x;
   //private ViewFlipper vf;
 
    public StatisticListAdapter(Context context, List<String> listDataHeader,
            HashMap<String, ArrayList<String>> listChildData) {
        this._context = context;
        this._listDataHeader = listDataHeader;       
        this._listDataChild = listChildData;
        //vf = null;
    }
    public static class ViewHolder {
        protected ViewFlipper vf;
    }
 
    @Override
    public Object getChild(int groupPosition, int childPosititon) {
        return this._listDataChild.get(this._listDataHeader.get(groupPosition))
                .get(childPosititon);
    }
 
    @Override
    public long getChildId(int groupPosition, int childPosition) {
        return childPosition;
    }
 
    @Override
    public View getChildView(int groupPosition, final int childPosition,
            boolean isLastChild, View convertView, ViewGroup parent) {
 
        //final String childText = (String) getChild(groupPosition, childPosition);
    	View view = null;
        if (convertView == null) {
        	LayoutInflater infalInflater = (LayoutInflater) this._context
                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        	view = infalInflater.inflate(R.layout.statistic_item, null);
        	//view = getLayoutInflater().inflate(R.layout.statistic_item, null);
        	final ViewHolder childHolder = new ViewHolder();
        	childHolder.vf = (ViewFlipper) view.findViewById(R.id.viewFlipper);
        	childHolder.vf.setOnTouchListener(new OnTouchListener(){

				@Override
				public boolean onTouch(View v, MotionEvent event) {
				switch (event.getAction()) {
	            case MotionEvent.ACTION_DOWN: //Cuando el usuario toca la pantalla por primera vez
	                init_x=event.getX();
	                	
	                return true;
	            case MotionEvent.ACTION_UP: //Cuando el usuario deja de presionar
	                float distance =init_x-event.getX();
	                if(distance>20)
	                {
	                     childHolder.vf.setInAnimation(inFromRightAnimation());
	                     childHolder.vf.setOutAnimation(outToLeftAnimation());
	                     childHolder.vf.showPrevious();
	                }

	                if(distance<-20)
	                {
	                	childHolder.vf.setInAnimation(inFromLeftAnimation());
	                	childHolder.vf.setOutAnimation(outToRightAnimation());
	                	childHolder.vf.showNext();
	                }
	                break;
	            case MotionEvent.ACTION_CANCEL: //Cuando el usuario deja de presionar
	                float distance1 =init_x-event.getX();
	                if(distance1>20)
	                {
	                     childHolder.vf.setInAnimation(inFromRightAnimation());
	                     childHolder.vf.setOutAnimation(outToLeftAnimation());
	                     childHolder.vf.showPrevious();
	                }

	                if(distance1<-20)
	                {
	                	childHolder.vf.setInAnimation(inFromLeftAnimation());
	                	childHolder.vf.setOutAnimation(outToRightAnimation());
	                	childHolder.vf.showNext();
	                }

	            default:
	                break;
	            }

	            return false;
        		
				}});
        	view.setTag(childHolder);
            childHolder.vf.setTag(_listDataChild.get(_listDataHeader.get(groupPosition)).get(childPosition));
        	}else{
        		view = convertView;         
                ((ViewHolder) view.getTag()).vf.setTag(_listDataChild.get(_listDataHeader.get(groupPosition)).get(childPosition));
        	}
        //Para el grafico de barras aqui podremos usarlo!!!!!!!.
        // Remove all current series from each plot
        
        /*Iterator<XYSeries> iterator1 = plot.getSeriesSet().iterator();
        while(iterator1.hasNext()) { 
        	XYSeries setElement = iterator1.next();
        	plot.removeSeries(setElement);
        }

        // Setup our Series with the selected number of elements
        series1 = new SimpleXYSeries(Arrays.asList(series1Numbers), SimpleXYSeries.ArrayFormat.Y_VALS_ONLY, "Us");
        series2 = new SimpleXYSeries(Arrays.asList(series2Numbers), SimpleXYSeries.ArrayFormat.Y_VALS_ONLY, "Them");

        // add a new series' to the xyplot:
        if (series1CheckBox.isChecked()) plot.addSeries(series1, formatter1);
        if (series2CheckBox.isChecked()) plot.addSeries(series2, formatter2); 

        // Setup the BarRenderer with our selected options
        MyBarRenderer renderer = ((MyBarRenderer)plot.getRenderer(MyBarRenderer.class));
        renderer.setBarRenderStyle((BarRenderer.BarRenderStyle)spRenderStyle.getSelectedItem());
        renderer.setBarWidthStyle((BarRenderer.BarWidthStyle)spWidthStyle.getSelectedItem());
        renderer.setBarWidth(sbFixedWidth.getProgress());
        renderer.setBarGap(sbVariableWidth.getProgress());
        
        if (BarRenderer.BarRenderStyle.STACKED.equals(spRenderStyle.getSelectedItem())) {
        	plot.setRangeTopMin(15);
        } else {
        	plot.setRangeTopMin(0);
        }
	        
        plot.redraw();
    	
    }  */
            
        	ViewHolder holder = (ViewHolder)view.getTag();
        mySimplePiePlot = (PieChart) holder.vf.findViewById(R.id.mySimplePiePlot);
        
        final String childText = (String) getChild(groupPosition, childPosition);
        String[] arrayEstadisticas = childText.split("%&");
       
        Segment segment1 = new Segment("Yo: " + arrayEstadisticas[4], Integer.parseInt(arrayEstadisticas[4]));
 
        Segment segment2 = new Segment("Por encima: " + arrayEstadisticas[5], Integer.parseInt(arrayEstadisticas[5]));
        
        Segment segment3 = new Segment("por debajo: " + arrayEstadisticas[3], Integer.parseInt(arrayEstadisticas[3]));
        

        SegmentFormatter segment1Format = new SegmentFormatter(Color.rgb(0, 200, 0));
        SegmentFormatter segment2Format = new SegmentFormatter(Color.rgb(0, 0, 500));
        SegmentFormatter segment3Format = new SegmentFormatter(Color.rgb(250, 200, 100));
        new SegmentFormatter();
        // Una vez definida la serie (datos y estilo), la añadimos al panel
        //mySimplePiePlot.addSeries(series1, series1Format);
        mySimplePiePlot.addSeries(segment1, segment1Format);
        if (Integer.parseInt(arrayEstadisticas[5])!=0){
        mySimplePiePlot.addSeries(segment2, segment2Format);
        }
        if(Integer.parseInt(arrayEstadisticas[3])!= 0){
        mySimplePiePlot.addSeries(segment3, segment3Format);
        }
        
 
        // Repetimos para la segunda serie
        //mySimplePiePlot.addSeries(series2, new LineAndPointFormatter
//(Color.rgb(0, 0, 200), Color.rgb(0, 0, 100), Color.rgb(150, 150, 190), null));
       
 
        TextView txtListChild1 = (TextView) view.findViewById(R.id.St1);
        TextView txtListChild2 = (TextView) view.findViewById(R.id.St2);
        TextView txtListChild3 = (TextView) view.findViewById(R.id.St3);
        
        txtListChild1.setText("Numero de tokens: "+arrayEstadisticas[0]);
        txtListChild2.setText("Numero de tokens registrados: " + arrayEstadisticas[1]);
        txtListChild3.setText("Alumno con mas tokens: " + arrayEstadisticas[2]);


 

 
        
        return view;
    }
 
    private LayoutInflater getLayoutInflater() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
    public int getChildrenCount(int groupPosition) {
    	int i = this._listDataChild.get(this._listDataHeader.get(groupPosition)).size(); 
        return i;
    }
    
   /* private class ListenerTouchViewFlipper implements View.OnTouchListener{
   	 
        @Override
        public boolean onTouch(View v, MotionEvent event) {

            switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN: //Cuando el usuario toca la pantalla por primera vez
                init_x=event.getX();
                	
                return true;
            case MotionEvent.ACTION_UP: //Cuando el usuario deja de presionar
                float distance =init_x-event.getX();
                if(distance>20)
                {
                     ch.vf.setInAnimation(inFromRightAnimation());
                     ch.vf.setOutAnimation(outToLeftAnimation());
                     ch.vf.showPrevious();
                }

                if(distance<-20)
                {
                     ch.vf.setInAnimation(inFromLeftAnimation());
                     ch.vf.setOutAnimation(outToRightAnimation());
                     ch.vf.showNext();
                }

            default:
                break;
            }

            return false;
        }

    }*/
    
    private Animation inFromRightAnimation() {

        Animation inFromRight = new TranslateAnimation(
        Animation.RELATIVE_TO_PARENT,  +1.0f, Animation.RELATIVE_TO_PARENT,  0.0f,
        Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,   0.0f );

        inFromRight.setDuration(500);
        inFromRight.setInterpolator(new AccelerateInterpolator());

        return inFromRight;

    }

    private Animation outToLeftAnimation() {
        Animation outtoLeft = new TranslateAnimation(
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, -1.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f);
        outtoLeft.setDuration(500);
        outtoLeft.setInterpolator(new AccelerateInterpolator());
        return outtoLeft;
    }

    private Animation inFromLeftAnimation() {
        Animation inFromLeft = new TranslateAnimation(
                Animation.RELATIVE_TO_PARENT, -1.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f);
        inFromLeft.setDuration(500);
        inFromLeft.setInterpolator(new AccelerateInterpolator());
        return inFromLeft;
    }

    private Animation outToRightAnimation() {
        Animation outtoRight = new TranslateAnimation(
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, +1.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f,
                Animation.RELATIVE_TO_PARENT, 0.0f);
        outtoRight.setDuration(500);
        outtoRight.setInterpolator(new AccelerateInterpolator());
        return outtoRight;
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
    public View getGroupView(int groupPosition, boolean isExpanded,
            View convertView, ViewGroup parent) {
        String headerTitle = (String) getGroup(groupPosition);
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) this._context
                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.list_group, null);
        }
 
        TextView lblListHeader = (TextView) convertView.findViewById(R.id.lblListHeader);
        lblListHeader.setTypeface(null, Typeface.BOLD);
        lblListHeader.setText(headerTitle);
 
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
}
