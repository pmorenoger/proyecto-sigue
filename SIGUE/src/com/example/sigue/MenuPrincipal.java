package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;

import android.os.AsyncTask;
import android.os.Bundle;

import android.util.AttributeSet;
import android.view.Gravity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;

import android.widget.Button;
import android.widget.ExpandableListView;
import android.widget.ExpandableListView.OnChildClickListener;
import android.widget.ExpandableListView.OnGroupClickListener;
import android.widget.ExpandableListView.OnGroupCollapseListener;
import android.widget.ExpandableListView.OnGroupExpandListener;
import android.widget.Toast;
import android.widget.LinearLayout.LayoutParams;
import android.widget.TabHost;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.TextView;



import com.example.sigue.library.DataBaseHandler;
import com.example.sigue.library.UserFunctions;



public class MenuPrincipal extends Activity {
	protected TextView customFont;
	TextView CodeErrorMsg;
	String codigoQR;
	String codigoAsig;
	String uid;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	public static List<String> listDataHeader;
	public static HashMap<String, ArrayList<String>> listDataChild;
	private static boolean change = false;
	
    UserFunctions userFunction;

    Button btnLogout;
    private static String KEY_SUCCESS = "success";

    private static String KEY_ERROR = "error";

    private static String KEY_ERROR_MSG = "error_msg";

    private static String KEY_UID = "uid";

    private static String KEY_NAME = "name";

    private static String KEY_EMAIL = "email";

    private static String KEY_CREATED_AT = "created_at";

    @Override

    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);



        /**

         * Dashboard Screen for the application

         * */

        // Check login status in database

        userFunction = new UserFunctions();

        if(userFunction.isUserLoggedIn(getApplicationContext())){

        

       // user already logged in show databoard

            setContentView(R.layout.activity_menu_principal);
            Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
            customFont= makeTabIndicator("Mis Tokens");
            customFont.setTypeface(font);
            final TabHost tabs=(TabHost)findViewById(android.R.id.tabhost);            
        	tabs.setup();  	        
         
        	// get the listview
            expListView = (ExpandableListView) findViewById(R.id.lvExp);
            // preparing list data
            if(!change){	
            	prepareListData();
            }
     
            listAdapter = new ExpandableListAdapter(this, listDataHeader, listDataChild);
     
            // setting list adapter
            expListView.setAdapter(listAdapter);
        
        	
        	TabHost.TabSpec spec=tabs.newTabSpec("Mis Tokens");
        	spec.setContent(R.id.tab1);
        	spec.setIndicator(customFont);
        	tabs.addTab(spec);
        	
        	customFont= makeTabIndicator("Estadísticas");
            customFont.setTypeface(font);
        	 
        	spec=tabs.newTabSpec("Estadísticas");
        	spec.setContent(R.id.tab2);
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
        	
        	DataBaseHandler db = new DataBaseHandler(this);
        	
        	HashMap<String, String> usuario = db.getUserDetails();
        	
        	uid = usuario.get("uid");
        	if(!change){
        		new Asincrono2().execute(userFunction);   
        	}
        	}else{

            // user is not logged in show login screen

            Intent login = new Intent(getApplicationContext(), MainActivity.class);

            login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            startActivity(login);

            // Closing dashboard screen

            finish();

        }

    }

/*
 * Preparing the list data
 */
private void prepareListData(JSONObject json) {
    listDataHeader = new ArrayList<String>();
    listDataChild = new HashMap<String, ArrayList<String>>();
    ArrayList<String> tokens = new ArrayList<String>();
    JSONArray asig = null;
    JSONArray tok = null;
    JSONObject aux = null;
    String aux1 = null;
    try {
		 asig = json.getJSONArray("Asignaturas");
		 int i = asig.length();
		 for(int j=0;j<i;j++){
			 aux = asig.getJSONObject(j).getJSONObject("Asignatura").getJSONObject("Datos");
			 tok = asig.getJSONObject(j).getJSONObject("Asignatura").getJSONArray("Tokens");
			 aux1 = aux.getString("nombre")+"  grupo: " + aux.getString("grupo") + "   " + aux.getString("curso");
			 listDataHeader.add(aux1);
			 int k = tok.length();
			 for(int z=0;z<k;z++){
				 aux1 = tok.getJSONObject(z).getString("codigo")+ "   " + tok.getJSONObject(z).getString("fecha_alta");
				 tokens.add(aux1);
			 }
			 listDataChild.put(listDataHeader.get(j),(ArrayList<String>) tokens.clone() );
			 tokens.clear();
		 }
	} catch (JSONException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	}
}

private void prepareListData() {
    listDataHeader = new ArrayList<String>();
    listDataChild = new HashMap<String, ArrayList<String>>();
   
    // Adding child data
    listDataHeader.add("Sin Datos");
}
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu){
    	getMenuInflater().inflate(R.menu.menu,menu);
		return true;
    	
    }
    
    @Override public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.Desvincular:
               desvincular();
               break;
        case R.id.Escanear:
        	Intent intent = new Intent("com.example.sigue.SCAN");
			intent.putExtra("SCAN_MODE", "QR_CODE_MODE"); 
			startActivityForResult(intent, 0);
            break;
        case R.id.Actualizar:
        	new Asincrono2().execute(userFunction);
        
        }
        return true; /** true -> consumimos el item, no se propaga*/
}
    
    private void desvincular(){
    	 userFunction.logoutUser(getApplicationContext());

         Intent login = new Intent(getApplicationContext(), MainActivity.class);

         login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

         startActivity(login);

         // Closing dashboard screen

         finish();

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

    public void onActivityResult(int requestCode, int resultCode, Intent intent) {

	    if (requestCode == 0) {

	        if (resultCode == RESULT_OK) {

	            String contenido = intent.getStringExtra("SCAN_RESULT");
	            String formato = intent.getStringExtra("SCAN_RESULT_FORMAT");

	            // Hacer algo con los datos obtenidos.
	            try{
	            String [] parametros = new String[2] ;
	            	parametros[0] = contenido.substring(0, 15);
	            	parametros[1] = contenido.substring(15);
	            		
	            	codigoQR = parametros[0];
	            	codigoAsig = parametros[1];
	            	DataBaseHandler db = new DataBaseHandler(this);
	            	HashMap<String, String> usuario = db.getUserDetails();
	            	uid = usuario.get("uid");
	            }catch(NullPointerException e){
	            	Toast toast = Toast.makeText(this, "Codigo QR no válido", Toast.LENGTH_SHORT);
	                toast.show();
	            }
	            //userFunction = new UserFunctions();
				new Asincrono1().execute(userFunction);
	  

	        } else if (resultCode == RESULT_CANCELED) {

	            // Si se cancelo la captura.

	        }

	    }

	}
    
    private void refresh() {

    	change = true;

    	finish();

    	Intent myIntent = new Intent(this, MenuPrincipal.class);

    	startActivity(myIntent);

    	}


	
	
	
private class Asincrono1 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MenuPrincipal.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        @Override
    	protected JSONObject doInBackground(UserFunctions... userfunction) {
        	JSONObject json = userFunction.qrRegister(codigoQR, codigoAsig, uid);
    		return json;
    	}
        

	@Override
        protected void onPostExecute(JSONObject json) {
		 // check for login response
		if (this.dialog.isShowing()) {
            this.dialog.dismiss();
        }

        
	    }
	}	
private class Asincrono2 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MenuPrincipal.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        @Override
    	protected JSONObject doInBackground(UserFunctions... userfunction) {
        	JSONObject json = userFunction.getSubjects(uid);
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
    

}