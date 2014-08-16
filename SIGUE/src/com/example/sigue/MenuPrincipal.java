package com.example.sigue;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.DialogInterface.OnCancelListener;
import android.graphics.Color;
import android.graphics.Typeface;

import android.os.AsyncTask;
import android.os.Bundle;

import android.util.AttributeSet;
import android.util.Log;
import android.view.ContextMenu;
import android.view.Gravity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.View.OnTouchListener;
import android.view.animation.AccelerateInterpolator;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;

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
import android.widget.ViewFlipper;



import com.androidplot.pie.PieChart;
import com.androidplot.pie.Segment;
import com.androidplot.pie.SegmentFormatter;
import com.androidplot.xy.LineAndPointFormatter;
import com.androidplot.xy.SimpleXYSeries;
import com.androidplot.xy.XYPlot;
import com.androidplot.xy.XYSeries;
import com.example.sigue.library.DataBaseHandler;
import com.example.sigue.library.UserFunctions;
import com.google.android.gcm.GCMRegistrar;
import com.google.android.gms.gcm.GoogleCloudMessaging;



public class MenuPrincipal extends Activity {
	protected TextView customFont;
	TextView CodeErrorMsg;
	String codigoQR;
	String codigoAsig;
	String uid;
	ExpandableListAdapter listAdapter;
	StatisticListAdapter listAdapter1;
	ExpandableListView expListView;
	ExpandableListView statisticView;
	private String[] MenuItems = {"Actividades"};
	public static ArrayList<String> listDataHeader;
	public static HashMap<String, ArrayList<String>> listDataChild;
	public static HashMap<String, ArrayList<String>> listStatisticChild;
	private static HashMap<String, ActividadesLista> listActividades;
	private static String pesoTokens;
    

	
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
        	DataBaseHandler db = new DataBaseHandler(this);
        	
        	HashMap<String, String> usuario = db.getUserDetails();
        	if(usuario.get("profesor").equals("1")){
        		 // user is not logged in show login screen

                Intent login = new Intent(getApplicationContext(), MenuPrincipalProfesor.class);

                login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                startActivity(login);

                // Closing dashboard screen

                finish();
        	}else{
        

       // user already logged in show databoard

            setContentView(R.layout.activity_menu_principal);
            Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
            customFont= makeTabIndicator("Mis Tokens");
            customFont.setTypeface(font);
            final TabHost tabs=(TabHost)findViewById(R.id.tabhost);            
        	tabs.setup();  	        
         
        	// get the listview
            expListView = (ExpandableListView) findViewById(R.id.lvExp);
            statisticView = (ExpandableListView) findViewById(R.id.lvSts);
            registerForContextMenu(expListView);
            // preparing list data
           
            prepareListData();
         
     
            listAdapter = new ExpandableListAdapter(this, listDataHeader, listDataChild);
            listAdapter1 = new StatisticListAdapter(this, listDataHeader, listStatisticChild);
     
            // setting list adapter
            expListView.setAdapter(listAdapter);
            statisticView.setAdapter(listAdapter1);       
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
        	
        	
        	
        	uid = usuario.get("uid");
        	
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
    listStatisticChild = new HashMap<String, ArrayList<String>>();
    listActividades = new HashMap<String, ActividadesLista>();
    ArrayList<String> tokens = new ArrayList<String>();
    ArrayList<String> statistics = new ArrayList<String>();
    ActividadesLista actividades = new ActividadesLista();
    JSONArray asig = null;
    JSONArray tok = null;
    JSONObject sts = null;
    JSONObject aux = null;
    JSONArray act = null;
    String aux1 = null;
    String notaTokens;
    float prov;
    try { 
		 asig = json.getJSONArray("Asignaturas");
		 int i = asig.length();
		 for(int j=0;j<i;j++){
			 aux = asig.getJSONObject(j).getJSONObject("Asignatura").getJSONObject("Datos");
			 sts= asig.getJSONObject(j).getJSONObject("Asignatura").getJSONObject("Estadisticas");
			 tok = asig.getJSONObject(j).getJSONObject("Asignatura").getJSONArray("Tokens");
			 act = asig.getJSONObject(j).getJSONObject("Asignatura").getJSONArray("Actividades");
			 notaTokens = asig.getJSONObject(j).getJSONObject("Asignatura").getString("notaTokens");
			 pesoTokens = asig.getJSONObject(j).getJSONObject("Asignatura").getString("pesoTokens");
			 aux1 = aux.getString("nombre")+"  grupo: " + aux.getString("grupo") + "   " + aux.getString("curso");
			 listDataHeader.add(aux1);	 
			 
			 
			 int k = tok.length();
			 for(int z=0;z<k;z++){
				 aux1 = tok.getJSONObject(z).getString("codigo")+ "   " + tok.getJSONObject(z).getString("fecha_alta");
				 tokens.add(aux1);
			 }
			 if (k==0){
				 tokens.add("Sin tokens");
			 }
			 listDataChild.put(listDataHeader.get(j),(ArrayList<String>) tokens.clone() );
			 tokens.clear();
			 
			 k = act.length();
			 String nombre;
			 String descripcion;
			 String nota;
			 String peso;
			 String observaciones;
			 int id;
			 Actividad act2;
			 JSONObject actaux;
			 for (int z=0;z<k;z++){
				actaux = act.getJSONObject(z);
				nombre = actaux.getString("Nombre");
				descripcion = actaux.getString("Descripcion");
				nota = actaux.getString("Nota");
				peso = actaux.getString("Peso");
				observaciones = actaux.getString("Observaciones");
				id = actaux.getInt("id");
				act2 = new Actividad(nombre,descripcion,nota,peso,observaciones,id);
				actividades.add(act2);
			 }
			 
			//aqui insertamos la actividad de los tokens. con su peso y su nota.
			 nombre = "Nota Tokens.";
			 descripcion = "Seguimiento del trabajo diario.";
			 observaciones = "Sin observaciones";
			 id = 0;
			 act2 = new Actividad(nombre,descripcion,notaTokens,pesoTokens,observaciones,id);
			 actividades.add(act2);
			 listActividades.put(listDataHeader.get(j), (ActividadesLista) actividades.clone());
			 actividades.clear();
			 prov = calculaProv(listDataHeader.get(j));
			 aux1 = sts.getString("MisTokens")+ "%&" + sts.getString("AllTokens") + "%&" + sts.getString("MaxTokens")
					 + "%&" + sts.getString("LessTokens") + "%&" + sts.getString("EqualTokens") + "%&" + sts.getString("MoreTokens") + "%&" + prov;
			 statistics.add(aux1);
			 listStatisticChild.put(listDataHeader.get(j), (ArrayList<String>)statistics.clone());
			 statistics.clear();
		 }
	} catch (NullPointerException e) {
		// TODO Auto-generated catch block
		Toast.makeText(MenuPrincipal.this, "Sin Resultados",
	            Toast.LENGTH_SHORT).show();
		e.printStackTrace();
	}catch (JSONException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	}
}





private void prepareListData() {
    listDataHeader = new ArrayList<String>();
    listDataChild = new HashMap<String, ArrayList<String>>();
    listStatisticChild = new HashMap<String, ArrayList<String>>();
   
    // Adding child data
    listDataHeader.add("Sin Datos");
}
    
public static void modActividades(int user, int pos, Actividad act){
	String aux = listDataHeader.get(user);
	listActividades.get(aux).set(pos, act);
}

    @Override
  public boolean onCreateOptionsMenu(Menu menu){
    	getMenuInflater().inflate(R.menu.menu,menu);
		return true;
    	
    }
    
    @Override public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.Desvincular:
               unregisterUser(this);
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
    
 // Context Menu Creation
    @Override
    public void onCreateContextMenu(ContextMenu menu, View v, ContextMenuInfo menuInfo) 
    {
        if (v.getId()==R.id.lvExp) 
        {            
            menu.setHeaderTitle("OPCIONES");
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
        int posicion = expListView.getPackedPositionGroup(info.packedPosition);
        //
        // Getting the Id
        int menuItemIndex = item.getItemId();
        if (menuItemIndex==0){
        	//creamos el intento y le pasamos la clase a mostrar
            Intent intent=new Intent(this,ActividadesActivity.class);
              Bundle contenedor=new Bundle();
           //le cargamos al bundle un objeto parcelable que se almacenara
              int user = expListView.getPackedPositionGroup(info.packedPosition);
             //bajo la key "array" y contendrá nuestra lista de libros
            contenedor.putParcelable("array",this.listActividades.get(listDataHeader.get(user)));
              //cargamos el intento con el bundle
            intent.putExtras(contenedor);
            intent.putExtra("usuario", user);
            intent.putExtra("profesor", false);
            intent.putExtra("asignatura", listDataHeader.get(user));
              //lanzamos el intento
            startActivity(intent);
            }
            return true;
        }
    
    public float calculaProv(String usr){
    	float nota = 0;
    	ActividadesLista act = listActividades.get(usr);
    	int i = act.size();
    	for (int j=0;j<i;j++){
    		if((act.get(j).getNota()!="null")&&(act.get(j).getPeso()!="null")){
    		nota = nota + Float.parseFloat(act.get(j).getNota())*Float.parseFloat(act.get(j).getPeso());
    		}
    	}
		return nota;    	
    }
    
    private void desvincular(){
    	
    	DataBaseHandler db = new DataBaseHandler(getApplicationContext());
    	
    	HashMap<String,String> userdata = db.getUserDetails();
    	
    	 userFunction.logoutUser(getApplicationContext(), userdata.get("uid"));

         Intent login = new Intent(getApplicationContext(), MainActivity.class);

         login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
         
         SharedPreferences prefs = getSharedPreferences(MainActivity.class.getSimpleName(),Context.MODE_PRIVATE);
         
         SharedPreferences.Editor editor = prefs.edit();
         
         editor.clear();
         
         editor.commit();
         
         //unregisterUser(getApplicationContext());
         
         startActivity(login);

         db.resetTables();
         
         // Closing dashboard screen

         finish();

    }
    
    private void unregisterUser(Context context){
    	
    	 TareaUnregistroGCM tarea = new TareaUnregistroGCM();
         tarea.execute();
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
	            	codigoQR = contenido;
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

    	//change = true;
    	//listDataHeader=(ArrayList<String>) listDataHeaderPermanent.clone();
		//listDataChild = (HashMap<String, ArrayList<String>>) listDataChildPermanent.clone();
		listAdapter.clear();
        listAdapter.addAll(listDataHeader,listDataChild);
        listAdapter.notifyDataSetChanged();
        listAdapter1.clear();
        listAdapter1.addAll(listDataHeader,listStatisticChild);
        listAdapter1.notifyDataSetChanged();
    	/*finish();

    	Intent myIntent = new Intent(this, MenuPrincipal.class);

    	startActivity(myIntent);*/

    	}



    
    
    private class TareaUnregistroGCM extends AsyncTask<String,Integer,JSONObject>
	{
    	@Override
        protected JSONObject doInBackground(String... params)
    {
    		GoogleCloudMessaging gcm;
    		//final String regId = GCMRegistrar.getRegistrationId(context);
        	
                gcm = GoogleCloudMessaging.getInstance(getApplicationContext());
                desvincular();
        	try {
    			gcm.unregister();
    		} catch (IOException e) {
    			// TODO Auto-generated catch block
    			e.printStackTrace();
    		}
		return null;
    		
    }
	}
	
private class Asincrono1 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MenuPrincipal.this);
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
        	JSONObject json = userFunction.qrRegister(codigoQR, uid);
    		return json;
    	}
        

	@Override
        protected void onPostExecute(JSONObject json) {
		 // check for login response
		if (this.dialog.isShowing()) {
            this.dialog.dismiss();
        }
		new Asincrono2().execute(userFunction);
        
	    }
	
	@Override
    protected void onCancelled() {
        Toast.makeText(MenuPrincipal.this, "Tarea cancelada!",
            Toast.LENGTH_SHORT).show();
    }
	}	
private class Asincrono2 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MenuPrincipal.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setOnCancelListener(new OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialog) {
                    Asincrono2.this.cancel(true);
                }
            });
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

	@Override
    protected void onCancelled() {
        Toast.makeText(MenuPrincipal.this, "Tarea cancelada!",
            Toast.LENGTH_SHORT).show();
    }
	}
    

}