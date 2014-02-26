package com.example.sigue;

import java.text.DecimalFormat;
import java.text.FieldPosition;
import java.text.Format;
import java.text.NumberFormat;
import java.text.ParseException;
import java.text.ParsePosition;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.DashPathEffect;
import android.graphics.LinearGradient;
import android.graphics.Paint;
import android.graphics.PointF;
import android.graphics.Shader;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.util.Pair;
import android.view.ContextMenu;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.Gravity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.widget.AdapterView;
import android.widget.ExpandableListView;
import android.widget.SearchView;
import android.widget.SearchView.OnQueryTextListener;
import android.widget.TabHost;
import android.widget.TextView;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.Toast;
import android.view.View;
import android.view.animation.AnimationUtils;
import android.widget.ViewFlipper;

import com.androidplot.LineRegion;
import com.androidplot.pie.PieChart;
import com.androidplot.pie.Segment;
import com.androidplot.pie.SegmentFormatter;
import com.androidplot.ui.AnchorPosition;
import com.androidplot.ui.SeriesRenderer;
import com.androidplot.ui.SizeLayoutType;
import com.androidplot.ui.SizeMetrics;
import com.androidplot.ui.TextOrientationType;
import com.androidplot.ui.XLayoutStyle;
import com.androidplot.ui.YLayoutStyle;
import com.androidplot.ui.widget.TextLabelWidget;
import com.androidplot.util.PixelUtils;
import com.androidplot.xy.*;
import com.example.sigue.library.DataBaseHandler;
import com.example.sigue.library.UserFunctions;

public class AlumnosActivity extends Activity {
	protected TextView customFont;
	TextView CodeErrorMsg;
	String codigoQR;
	String codigoAsig;
	int id;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	public static ArrayList<String> listDataHeader;
	public static HashMap<String, ArrayList<String>> listDataChild;
	public static ArrayList<String> listDataHeaderPermanent;
	public static HashMap<String, ArrayList<String>> listDataChildPermanent;
	private static boolean change = false;
	UserFunctions userFunction;
	private ViewFlipper vf;
	public float init_x;
	private PieChart mySimplePiePlot;
	private XYPlot plot;
	private XYPlot plot1;
	private static final String NO_SELECTION_TXT = "Toca una barra para seleccionar.";
	private XYSeries series1;
	private ArrayList<Number> series1Numbers;
	private ArrayList<String> fechas;
	private HashMap<String, Integer> registros;
	private ArrayList<Integer> seriesRegistros;
	private MyBarFormatter formatter1;

    private MyBarFormatter selectionFormatter;

    private TextLabelWidget selectionWidget;

    private Pair<Integer, XYSeries> selection;
    
    ArrayList<String> statistics;
    
    private String[] MenuItems = {"Detalles", "Actividades"};
	
	public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        /**

         * Dashboard Screen for the application

         * */
        DataBaseHandler db = new DataBaseHandler(this);
    	userFunction = new UserFunctions();
    	HashMap<String, String> usuario = db.getUserDetails();
    	series1Numbers = new ArrayList<Number>();
    	statistics = new ArrayList<String>();
    	fechas = new ArrayList<String>();
    	registros = new HashMap<String, Integer>();
    	seriesRegistros = new ArrayList<Integer>();    	
        setContentView(R.layout.activity_alumnos);
        vf = (ViewFlipper) findViewById(R.id.viewFlipper2);
        vf.setOnTouchListener(new ListenerTouchViewFlipper());
        Bundle bundle = getIntent().getExtras();
        Bundle extras = getIntent().getExtras();
        if ( extras == null ){
            Log.e("extras", "Extra NULL");
        } else {
        id = bundle.getInt("Asignatura");
        }
        Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
        customFont= makeTabIndicator("Alumnos");
        customFont.setTypeface(font);
        final TabHost tabs=(TabHost)findViewById(R.id.tabhost2);            
    	tabs.setup();  	        
     
    	// get the listview
        expListView = (ExpandableListView) findViewById(R.id.lvExp2);
        //create context menu
        registerForContextMenu(expListView);
        // preparing list data
        	
        	prepareListData();
        
 
        listAdapter = new ExpandableListAdapter(this, listDataHeader, listDataChild);
 
        // setting list adapter
        expListView.setAdapter(listAdapter);     
    	
    	TabHost.TabSpec spec=tabs.newTabSpec("Alumnos");
    	spec.setContent(R.id.tab12);
    	spec.setIndicator(customFont);
    	tabs.addTab(spec);
    	
    	customFont= makeTabIndicator("Estadísticas");
        customFont.setTypeface(font);
    	 
    	spec=tabs.newTabSpec("Estadísticas");
    	spec.setContent(R.id.tab22);
    	spec.setIndicator(customFont);
    	tabs.addTab(spec);
    	
    	tabs.getTabWidget().setBackgroundColor(Color.DKGRAY);
    	tabs.getTabWidget().setStripEnabled(true);
    	tabs.setOnTabChangedListener(new OnTabChangeListener() {
    	    @Override
    	    public void onTabChanged(String tabId) {
    	        setTabColor(tabs);
    	    }
    	});	        	
    	
    	tabs.setCurrentTab(0);
    	
    	setTabColor(tabs); 
    	
    	preparaGraficoBarras(R.id.barPlot);
    	
    	new Asincrono2().execute(userFunction);   
    	  	
        	
     }
	
	/*
	 * Preparing the list data
	 */
	private void prepareListData(JSONObject json) {
	    listDataHeader = new ArrayList<String>();
	    listDataChild = new HashMap<String, ArrayList<String>>();
	    //listStatisticChild = new HashMap<String, ArrayList<String>>();
	    ArrayList<String> tokens = new ArrayList<String>();
	    JSONArray asig = null;
	    JSONArray tok = null;
	    JSONObject sts = null;
	    JSONObject aux = null;
	    String aux1 = null;
	    fechas.clear();
	    registros.clear();
	    try {
			 asig = json.getJSONArray("Alumnos");
			 int i = asig.length();
			 statistics.clear();
			 for(int j=0;j<i;j++){
				 aux = asig.getJSONObject(j).getJSONObject("Alumno").getJSONObject("Datos");
				 
				 tok = asig.getJSONObject(j).getJSONObject("Alumno").getJSONArray("Tokens");
				 aux1 = aux.getString("nombre")+" " + aux.getString("apellidos") + " DNI:" + aux.getString("dni");
				 listDataHeader.add(aux1);	 
				 
				
				 int k = tok.length();
				 String f;
				 String faux;
				 int num;
				 SimpleDateFormat formateador = new SimpleDateFormat("yyyy-MM-dd");
				 Date fecha1;
				 Date fecha2;
				 for(int z=0;z<k;z++){
					 f=tok.getJSONObject(z).getString("fecha_alta");
					 faux = f.substring(0, 10);
					 aux1 = tok.getJSONObject(z).getString("codigo")+ "   " + f;
					 if(fechas.contains(faux)){
						 num = registros.get(faux);
						 registros.remove(faux);
						 registros.put(faux, num+1);
					 }else {
						 int x = fechas.size();
						 int y=0;
						 try {
							fecha1=formateador.parse(faux);	
							if(x==0){
								fechas.add(y, faux);
								registros.put(faux, 1);
							}else{
							fecha2=formateador.parse(fechas.get(y));
							while((y<x)&&!(fecha1.before(fecha2)) ){
								y++;
								if(y<x){
								fecha2=formateador.parse(fechas.get(y));
								}
							 }
								 fechas.add(y, faux);
								 registros.put(faux, 1);
							}
							
							 
							 
						} catch (ParseException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						
						
					 }
					 tokens.add(aux1);
				 }
				 listDataChild.put(listDataHeader.get(j),(ArrayList<String>) tokens.clone() );
				 tokens.clear();
			 }
			 sts= json.getJSONObject("Estadisticas");
			 //aux1 = sts.getString("Redeemed")+ "%&" + sts.getString("NotRedeemed");
			 //+ "%&" + sts.getString("LessTokens") + "%&" + sts.getString("EqualTokens") + "%&" + sts.getString("MoreTokens");
			 statistics.add(sts.getString("Redeemed"));
			 statistics.add(sts.getString("NotRedeemed"));
			 //listStatisticChild.put(listDataHeader.get(j), (ArrayList<String>)statistics.clone());
			 listDataHeaderPermanent = (ArrayList<String>) listDataHeader.clone();
			 listDataChildPermanent = (HashMap<String, ArrayList<String>>) listDataChild.clone();
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}





	private void prepareListData() {
	    listDataHeader = new ArrayList<String>();
	    listDataChild = new HashMap<String, ArrayList<String>>();
	    listDataHeaderPermanent = new ArrayList<String>();
	    listDataChildPermanent = new HashMap<String, ArrayList<String>>();
	   
	    // Adding child data
	    listDataHeader.add("Sin Datos");
	}
	
	private TextView makeTabIndicator(String text){

    	TextView tabView = new TextView(this);
    	tabView.setText(text);
    	tabView.setTextSize(1, 20);
    	tabView.setTextColor(Color.WHITE);
    	tabView.setGravity(Gravity.CENTER_HORIZONTAL|Gravity.CENTER_VERTICAL);
    	tabView.setPadding(13, 0, 13, 0);
    	return tabView;

    	}
	
	public void setTabColor(TabHost tabhost) {

        for(int i=0;i<tabhost.getTabWidget().getChildCount();i++)
            tabhost.getTabWidget().getChildAt(i).setBackgroundColor(Color.DKGRAY); //unselected

        if(tabhost.getCurrentTab()==0)
               tabhost.getTabWidget().getChildAt(tabhost.getCurrentTab()).setBackgroundColor(Color.parseColor("#ff888888")); //1st tab selected
        else
               tabhost.getTabWidget().getChildAt(tabhost.getCurrentTab()).setBackgroundColor(Color.parseColor("#ff888888")); //2nd tab selected
    }
	private void refresh() {

    	//change = true;

    	listDataHeader=(ArrayList<String>) listDataHeaderPermanent.clone();
		listDataChild = (HashMap<String, ArrayList<String>>) listDataChildPermanent.clone();
		listAdapter.clear();
        listAdapter.addAll(listDataHeader,listDataChild);
        listAdapter.notifyDataSetChanged();
        preparaGrafico(R.id.mySimplePiePlot3);
        
        updatePlot();
    	
    	
    	preparaGraficoTiempo(R.id.fechasPlot);
    	 

    	/*Intent i = new Intent(this, AlumnosActivity.class);
    	i.putExtra("Asignatura",id);
    	startActivity(i);
    	finish();*/
    	
    	}

	
	@Override
    public boolean onCreateOptionsMenu(Menu menu){
    	getMenuInflater().inflate(R.menu.menu3, menu);
    	final MenuItem searchItem = menu.findItem(R.id.search);
    	final SearchView searchView = (SearchView) searchItem.getActionView();
    	searchView.setOnQueryTextListener(new OnQueryTextListener(){

			@Override
			public boolean onQueryTextChange(String text) {
				// TODO Auto-generated method stub
				filtrarDatos(text);
				 listAdapter.clear();
                 listAdapter.addAll(listDataHeader,listDataChild);
                 listAdapter.notifyDataSetChanged();
				return false;
			}

			@Override
			public boolean onQueryTextSubmit(String query) {
				// TODO Auto-generated method stub
				return false;
			}
    		
    	});
    	
    	searchView.setOnQueryTextFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View view, boolean queryTextFocused) {
                if(!queryTextFocused) {
                    searchItem.collapseActionView();
                    searchView.setQuery("", false);
                    listDataHeader=(ArrayList<String>) listDataHeaderPermanent.clone();
            		listDataChild = (HashMap<String, ArrayList<String>>) listDataChildPermanent.clone();
            		listAdapter.clear();
                    listAdapter.addAll(listDataHeader,listDataChild);
                    listAdapter.notifyDataSetChanged();
                }
            }
        });
    	
		return true;
    	
    }
    
	// Context Menu Creation
    @Override
    public void onCreateContextMenu(ContextMenu menu, View v, ContextMenuInfo menuInfo) 
    {
        if (v.getId()==R.id.lvExp2) 
        {            
            menu.setHeaderTitle("CONTEXT MENU");
            for (int i = 0; i< MenuItems.length; i++) 
            {
                menu.add(Menu.NONE, i, i, MenuItems[i]);
            }
      }
    }
    
   // Context Menu Item Selection
    @Override
    public boolean onContextItemSelected(MenuItem item) 
    {
        ExpandableListView.ExpandableListContextMenuInfo info =(ExpandableListView.ExpandableListContextMenuInfo)item.getMenuInfo();
      
        // Getting the Id
        int menuItemIndex = item.getItemId();
        Toast.makeText(AlumnosActivity.this, "Clicked Item Position :"+expListView.getPackedPositionGroup(info.packedPosition)+"\n"+"Seleted Option Id :"+menuItemIndex, Toast.LENGTH_SHORT).show();        
        return true;
    }
    protected void filtrarDatos(String text) {
		// TODO Auto-generated method stub
    	String frase;
    	ArrayList<String> cabecera=new ArrayList<String>();
    	HashMap<String, ArrayList<String>> hijos=new HashMap<String, ArrayList<String>>();
		int i = listDataHeaderPermanent.size();
		for(int j=0;j<i;j++){
			frase=listDataHeaderPermanent.get(j);
			if(frase.indexOf(text)>=0){
				cabecera.add(listDataHeaderPermanent.get(j));
				hijos.put(frase, listDataChildPermanent.get(frase));
			}
		}
		listDataHeader=(ArrayList<String>) cabecera.clone();
		listDataChild = (HashMap<String, ArrayList<String>>) hijos.clone();
	}

	@Override public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.Desvincular3:
               desvincular();
               break;
        case R.id.Actualizar3:
        	new Asincrono2().execute(userFunction);
        	break;
        case R.id.search:
        	
        
        }
        return true; /** true -> consumimos el item, no se propaga*/
}
    
    private void desvincular(){
    	 userFunction.logoutUser(getApplicationContext());

         Intent login = new Intent(getApplicationContext(), MainActivity.class);

         login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK|Intent.FLAG_ACTIVITY_NEW_TASK);

         startActivity(login);
         
         change = false;
         
         DataBaseHandler db = new DataBaseHandler(this);
         db.resetTables();

         // Closing dashboard screen

         finish();

    }
    
private void seriesBarras(){
	series1Numbers.clear();
	int i = listDataHeader.size();
	for (int j=0;j<i;j++){
	series1Numbers.add(j, listDataChild.get(listDataHeader.get(j)).size());
	}
}

private void seriesTiempo(){
	seriesRegistros.clear();
	int i = fechas.size();
	for (int j=0;j<i;j++){
	seriesRegistros.add(j, registros.get(fechas.get(j)));
	}
}
    
 private void preparaGrafico(int id){
	 mySimplePiePlot = (PieChart) vf.findViewById(id);
	 mySimplePiePlot.clear();
	 int totales = Integer.parseInt(statistics.get(1))+Integer.parseInt(statistics.get(0));
	 int redimidos = (Integer.parseInt(statistics.get(0))/totales)*100;
	 int noredimidos = (Integer.parseInt(statistics.get(1))/totales)*100;
     Segment segment1 = new Segment("Sin redimir: " + Integer.toString(noredimidos)+"%", Integer.parseInt(statistics.get(1)));

     Segment segment2 = new Segment("Redimidos: " + Integer.toString(redimidos)+"%",Integer.parseInt(statistics.get(0)));
     
     //Segment segment3 = new Segment("por debajo: " + arrayEstadisticas[3], Integer.parseInt(arrayEstadisticas[3]));
     

     SegmentFormatter segment1Format = new SegmentFormatter(Color.rgb(0, 200, 0));
     SegmentFormatter segment2Format = new SegmentFormatter(Color.rgb(0, 0, 500));
     //SegmentFormatter segment3Format = new SegmentFormatter(Color.rgb(250, 200, 100));
     new SegmentFormatter();
     // Una vez definida la serie (datos y estilo), la añadimos al panel
     //mySimplePiePlot.addSeries(series1, series1Format);
     mySimplePiePlot.addSeries(segment1, segment1Format);
     //if (Integer.parseInt(arrayEstadisticas[5])!=0){
     mySimplePiePlot.addSeries(segment2, segment2Format);
     //}
     //if(Integer.parseInt(arrayEstadisticas[3])!= 0){
     //mySimplePiePlot.addSeries(segment3, segment3Format);
     //}
 }
 
 private void preparaGraficoTiempo(int id){
	 seriesTiempo();
	 
	 plot1 = (XYPlot) findViewById(id);
	 plot1.clear();
     // create our series from our array of nums:
     XYSeries series2 = new SimpleXYSeries(seriesRegistros,SimpleXYSeries.ArrayFormat.Y_VALS_ONLY, "Fechas");

     plot1.getGraphWidget().getGridBackgroundPaint().setColor(Color.WHITE);
     plot1.getGraphWidget().getDomainGridLinePaint().setColor(Color.BLACK);
     plot1.getGraphWidget().getDomainGridLinePaint().
             setPathEffect(new DashPathEffect(new float[]{1, 1}, 1));
     plot1.getGraphWidget().getRangeGridLinePaint().setColor(Color.BLACK);
     plot1.getGraphWidget().getRangeGridLinePaint().
             setPathEffect(new DashPathEffect(new float[]{1, 1}, 1));
     plot1.getGraphWidget().getDomainOriginLinePaint().setColor(Color.BLACK);
     plot1.getGraphWidget().getRangeOriginLinePaint().setColor(Color.BLACK);

     // Create a formatter to use for drawing a series using LineAndPointRenderer:
     LineAndPointFormatter series1Format = new LineAndPointFormatter(
             Color.rgb(0, 100, 0),                   // line color
             Color.rgb(0, 100, 0),                   // point color
             Color.rgb(100, 200, 0), null);                // fill color


     // setup our line fill paint to be a slightly transparent gradient:
     Paint lineFill = new Paint();
     lineFill.setAlpha(200);

     // ugly usage of LinearGradient. unfortunately there's no way to determine the actual size of
     // a View from within onCreate.  one alternative is to specify a dimension in resources
     // and use that accordingly.  at least then the values can be customized for the device type and orientation.
     lineFill.setShader(new LinearGradient(0, 0, 200, 200, Color.WHITE, Color.GREEN, Shader.TileMode.CLAMP));

     LineAndPointFormatter formatter  =
             new LineAndPointFormatter(Color.rgb(0, 0,0), Color.BLUE, Color.RED, new PointLabelFormatter(Color.BLACK));
     formatter.setFillPaint(lineFill);
     plot1.getGraphWidget().setPaddingRight(2);
     plot1.addSeries(series2, formatter);
     plot1.getGraphWidget().setDomainLabelOrientation(-45);
     plot1.getGraphWidget().setDomainLabelHorizontalOffset(-18);
     plot1.getGraphWidget().setDomainLabelVerticalOffset(18);
     // draw a domain tick for each year:
     plot1.setDomainStep(XYStepMode.SUBDIVIDE, fechas.size());
     plot1.setRangeStep(XYStepMode.INCREMENT_BY_VAL, fechas.size());
     plot1.setTicksPerRangeLabel(1);

     // customize our domain/range labels
     plot1.setDomainLabel("Fecha");
     plot1.setRangeLabel("Tokens Registrados");

     // get rid of decimal points in our range labels:
     plot1.setRangeValueFormat(new DecimalFormat("0"));
     

     plot1.setDomainValueFormat(new NumberFormat() {           

			@Override
			public StringBuffer format(double value, StringBuffer buffer,
					FieldPosition field) {
				// TODO Auto-generated method stub
             return new StringBuffer(fechas.get((int) value));
				
			}

			@Override
			public StringBuffer format(long value, StringBuffer buffer,
					FieldPosition field) {
				// TODO Auto-generated method stub
				throw new UnsupportedOperationException("Not yet implemented.");
				
			}

			@Override
			public Number parse(String string, ParsePosition position) {
				// TODO Auto-generated method stub
				throw new UnsupportedOperationException("Not yet implemented.");				
			}
     });

     // by default, AndroidPlot displays developer guides to aid in laying out your plot.
     // To get rid of them call disableAllMarkup():
     //plot1.disableAllMarkup();
 }
 
 private void preparaGraficoBarras(int id){
	 	
	 	plot = (XYPlot) findViewById(id);
	 	plot.clear();
	 	selectionWidget = new TextLabelWidget(plot.getLayoutManager(), NO_SELECTION_TXT,
                new SizeMetrics(
                        PixelUtils.dpToPix(100), SizeLayoutType.ABSOLUTE,
                        PixelUtils.dpToPix(100), SizeLayoutType.ABSOLUTE),
                        TextOrientationType.HORIZONTAL);

        selectionWidget.getLabelPaint().setTextSize(PixelUtils.dpToPix(16));
        
        // add a dark, semi-transparent background to the selection label widget:
        Paint p = new Paint();
        p.setARGB(100, 0, 0, 0);
        selectionWidget.setBackgroundPaint(p);

        selectionWidget.position(
                0, XLayoutStyle.RELATIVE_TO_CENTER,
                PixelUtils.dpToPix(45), YLayoutStyle.ABSOLUTE_FROM_TOP,
                AnchorPosition.TOP_MIDDLE);
        selectionWidget.pack();
        
     // reduce the number of range labels
        plot.setTicksPerRangeLabel(3);
        plot.setRangeLowerBoundary(0, BoundaryMode.FIXED);
        plot.getGraphWidget().setGridPadding(30, 10, 30, 0);

        plot.setTicksPerDomainLabel(1);
        
        plot.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                if(motionEvent.getAction() == MotionEvent.ACTION_DOWN) {
                    onPlotClicked(new PointF(motionEvent.getX(), motionEvent.getY()));
                }
                return false;
            }
        });
        
        plot.setDomainValueFormat(new NumberFormat() {           

			@Override
			public StringBuffer format(double value, StringBuffer buffer,
					FieldPosition field) {
				// TODO Auto-generated method stub
                return new StringBuffer(listDataHeader.get((int) value).substring(0,1));
				
			}

			@Override
			public StringBuffer format(long value, StringBuffer buffer,
					FieldPosition field) {
				// TODO Auto-generated method stub
				throw new UnsupportedOperationException("Not yet implemented.");
				
			}

			@Override
			public Number parse(String string, ParsePosition position) {
				// TODO Auto-generated method stub
				throw new UnsupportedOperationException("Not yet implemented.");				
			}
        });
        formatter1 = new MyBarFormatter(Color.argb(200, 100, 150, 100), Color.LTGRAY);
    	selectionFormatter = new MyBarFormatter(Color.YELLOW, Color.WHITE);
        //updatePlot();
 }
 
 private void updatePlot() {
	 seriesBarras();
 	// Remove all current series from each plot
     Iterator<XYSeries> iterator1 = plot.getSeriesSet().iterator();
     while(iterator1.hasNext()) { 
     	XYSeries setElement = iterator1.next();
     	plot.removeSeries(setElement);
     }

     // Setup our Series with the selected number of elements
     series1 = new SimpleXYSeries(series1Numbers, SimpleXYSeries.ArrayFormat.Y_VALS_ONLY, "Tokens");
     

     // add a new series' to the xyplot:
     plot.addSeries(series1, formatter1); 

     // Setup the BarRenderer with our selected options
     MyBarRenderer renderer = ((MyBarRenderer)plot.getRenderer(MyBarRenderer.class));
     renderer.setBarRenderStyle(BarRenderer.BarRenderStyle.OVERLAID);
     renderer.setBarWidthStyle(BarRenderer.BarWidthStyle.VARIABLE_WIDTH);
     renderer.setBarWidth(0);
     renderer.setBarGap(0);
     plot.setRangeTopMin(0);       
     plot.redraw();
 	
 } 
 
 private void onPlotClicked(PointF point) {

     // make sure the point lies within the graph area.  we use gridrect
     // because it accounts for margins and padding as well. 
     if (plot.getGraphWidget().getGridRect().contains(point.x, point.y)) {
         Number x = plot.getXVal(point);
         Number y = plot.getYVal(point);


         selection = null;
         double xDistance = 0;
         double yDistance = 0;

         // find the closest value to the selection:
         for (XYSeries series : plot.getSeriesSet()) {
             for (int i = 0; i < series.size(); i++) {
                 Number thisX = series.getX(i);
                 Number thisY = series.getY(i);
                 if (thisX != null && thisY != null) {
                     double thisXDistance =
                             LineRegion.measure(x, thisX).doubleValue();
                     double thisYDistance =
                             LineRegion.measure(y, thisY).doubleValue();
                     if (selection == null) {
                         selection = new Pair<Integer, XYSeries>(i, series);
                         xDistance = thisXDistance;
                         yDistance = thisYDistance;
                     } else if (thisXDistance < xDistance) {
                         selection = new Pair<Integer, XYSeries>(i, series);
                         xDistance = thisXDistance;
                         yDistance = thisYDistance;
                     } else if (thisXDistance == xDistance &&
                             thisYDistance < yDistance &&
                             thisY.doubleValue() >= y.doubleValue()) {
                         selection = new Pair<Integer, XYSeries>(i, series);
                         xDistance = thisXDistance;
                         yDistance = thisYDistance;
                     }
                 }
             }
         }

     } else {
         // if the press was outside the graph area, deselect:
         selection = null;
     }

     if(selection == null) {
         selectionWidget.setText(NO_SELECTION_TXT);
     } else {
         selectionWidget.setText("Nombre: " + listDataHeader.get(selection.first)/*selection.second.getX(selection.first)*/ +
                 " Tokens: " + selection.second.getY(selection.first));
     }
     plot.redraw();
 }
 
private class MyBarFormatter extends BarFormatter {
     public MyBarFormatter(int fillColor, int borderColor) {
         super(fillColor, borderColor);
     }

     @Override
     public Class<? extends SeriesRenderer> getRendererClass() {
         return MyBarRenderer.class;
     }

     @Override
     public SeriesRenderer getRendererInstance(XYPlot plot) {
         return new MyBarRenderer(plot);
     }
 }

 private class MyBarRenderer extends BarRenderer<MyBarFormatter> {

     public MyBarRenderer(XYPlot plot) {
         super(plot);
     }

	public MyBarFormatter getFormatter(XYSeries series){
        	return formatter1;
    
	}

	/**
      * Implementing this method to allow us to inject our
      * special selection formatter.
      * @param index index of the point being rendered.
      * @param series XYSeries to which the point being rendered belongs.
      * @return
      */
	
     public MyBarFormatter getFormatter(int index, XYSeries series) { 
         if(selection != null &&
                 selection.second == series && 
                 selection.first == index) {
             return selectionFormatter;
         } else {
             return getFormatter(series);
         }
     }
 }
 
private class Asincrono2 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(AlumnosActivity.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        @Override
    	protected JSONObject doInBackground(UserFunctions... userfunction) {
        	JSONObject json = userFunction.getAlumnos(id);
    		return json;
    	}
        

	@Override
        protected void onPostExecute(JSONObject json) {
		 // check for login response
		prepareListData(json);
		refresh();
		if (this.dialog.isShowing()) {
            this.dialog.dismiss();
        }
	    }

	
	}
private class ListenerTouchViewFlipper implements View.OnTouchListener{
	 
    @Override
    public boolean onTouch(View v, MotionEvent event) {

        switch (event.getAction()) {
        case MotionEvent.ACTION_DOWN: //Cuando el usuario toca la pantalla por primera vez
            init_x=event.getX();
            return true;
        case MotionEvent.ACTION_UP: //Cuando el usuario deja de presionar
            float distance =init_x-event.getX();

            if(distance>100)
            {
                 vf.setInAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_in_right));
                 vf.setOutAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_out_left));
                 vf.showPrevious();
            }

            if(distance<-100)
            {	
                 vf.setInAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_in_left));
                 vf.setOutAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_out_right));
                 vf.showNext();
            }

        default:
            break;
        }

        return false;
    }

}
}
