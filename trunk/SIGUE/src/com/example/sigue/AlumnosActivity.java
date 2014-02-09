package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ProgressDialog;
import android.app.SearchManager;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View.OnFocusChangeListener;
import android.widget.ExpandableListView;
import android.widget.SearchView;
import android.widget.SearchView.OnCloseListener;
import android.widget.SearchView.OnQueryTextListener;
import android.widget.TabHost;
import android.widget.TextView;
import android.widget.TabHost.OnTabChangeListener;
import android.view.View;
import android.view.animation.AnimationUtils;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.ViewFlipper;

import com.androidplot.pie.PieChart;
import com.androidplot.pie.Segment;
import com.androidplot.pie.SegmentFormatter;
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
	
	public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        /**

         * Dashboard Screen for the application

         * */
        DataBaseHandler db = new DataBaseHandler(this);
    	userFunction = new UserFunctions();
    	HashMap<String, String> usuario = db.getUserDetails();

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
        customFont= makeTabIndicator("Mis Tokens");
        customFont.setTypeface(font);
        final TabHost tabs=(TabHost)findViewById(R.id.tabhost2);            
    	tabs.setup();  	        
     
    	// get the listview
        expListView = (ExpandableListView) findViewById(R.id.lvExp2);
        // preparing list data
        if(!change){	
        	prepareListData();
        }
 
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
    	
    	preparaGrafico(R.id.mySimplePiePlot2);
    	preparaGrafico(R.id.mySimplePiePlot3);
    	
    	
		
    	if(!change){
    		new Asincrono2().execute(userFunction);   
    	}  	
        	
     }
	
	/*
	 * Preparing the list data
	 */
	private void prepareListData(JSONObject json) {
	    listDataHeader = new ArrayList<String>();
	    listDataChild = new HashMap<String, ArrayList<String>>();
	    //listStatisticChild = new HashMap<String, ArrayList<String>>();
	    ArrayList<String> tokens = new ArrayList<String>();
	    ArrayList<String> statistics = new ArrayList<String>();
	    JSONArray asig = null;
	    JSONArray tok = null;
	    JSONObject sts = null;
	    JSONObject aux = null;
	    String aux1 = null;
	    try {
			 asig = json.getJSONArray("Alumnos");
			 int i = asig.length();
			 for(int j=0;j<i;j++){
				 aux = asig.getJSONObject(j).getJSONObject("Alumno").getJSONObject("Datos");
				 //sts= asig.getJSONObject(j).getJSONObject("Alumno").getJSONObject("Estadisticas");
				 tok = asig.getJSONObject(j).getJSONObject("Alumno").getJSONArray("Tokens");
				 aux1 = aux.getString("nombre")+" " + aux.getString("apellidos") + " DNI:" + aux.getString("dni");
				 listDataHeader.add(aux1);	 
				 
				 //aux1 = sts.getString("MisTokens")+ "%&" + sts.getString("AllTokens") + "%&" + sts.getString("MaxTokens")
						 //+ "%&" + sts.getString("LessTokens") + "%&" + sts.getString("EqualTokens") + "%&" + sts.getString("MoreTokens");
				 //statistics.add(aux1);
				 //listStatisticChild.put(listDataHeader.get(j), (ArrayList<String>)statistics.clone());
				 statistics.clear();
				 int k = tok.length();
				 for(int z=0;z<k;z++){
					 aux1 = tok.getJSONObject(z).getString("codigo")+ "   " + tok.getJSONObject(z).getString("fecha_alta");
					 tokens.add(aux1);
				 }
				 listDataChild.put(listDataHeader.get(j),(ArrayList<String>) tokens.clone() );
				 tokens.clear();
			 }
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

    	change = true;

    	

    	Intent i = new Intent(this, AlumnosActivity.class);
    	i.putExtra("Asignatura",id);
    	startActivity(i);
    	finish();
    	
    	}
	
	public boolean onOptionsItemSelected1(MenuItem item) {
	    switch (item.getItemId()) {
	        case R.id.search:
	            onSearchRequested();
	            return true;
	        default:
	            return false;
	    }
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

         login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

         startActivity(login);
         
         change = false;
         
         DataBaseHandler db = new DataBaseHandler(this);
         db.resetTables();

         // Closing dashboard screen

         finish();

    }
    
 private void preparaGrafico(int id){
	 mySimplePiePlot = (PieChart) vf.findViewById(id);
     
     //String[] arrayEstadisticas = childText.split("%&");
    
     Segment segment1 = new Segment("Sin redimir: 24", 24);

     Segment segment2 = new Segment("Redimidos: 10", 10);
     
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

            if(distance>0)
            {
                 vf.setInAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_in_right));
                 vf.setOutAnimation(AnimationUtils.loadAnimation(v.getContext(), R.anim.slide_out_left));
                 vf.showPrevious();
            }

            if(distance<0)
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
