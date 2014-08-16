package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;



import com.example.sigue.library.DataBaseHandler;
import com.example.sigue.library.UserFunctions;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ExpandableListView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;


public class MenuPrincipalProfesor extends Activity  {
	protected TextView customFont;
	ListAdapter listAdapter;
	ListView expListView;
	String uid;
	public static List<String> listDataHeader;
	public static HashMap<String, ArrayList<String>> listDataChild;
	public static ArrayList<Integer> asigid;
	private static boolean change = false;
	UserFunctions userFunction;
	
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		userFunction = new UserFunctions();		
		DataBaseHandler db = new DataBaseHandler(this);
		HashMap<String, String> usuario = db.getUserDetails();
		
		setContentView(R.layout.activity_main_profesor);
		Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
		customFont = (TextView) findViewById(R.id.tituloprofe);
		customFont.setTypeface(font);
		expListView = (ListView)findViewById(R.id.lvProf);
		
		/*poner esto en el xml, <ExpandableListView
	      android:id="@+id/lvProf"
	      android:layout_height="match_parent"
	      android:layout_width="match_parent"/>*/
		
		// preparing list data
        if(!change){	
        	prepareListData();
        	asigid = new ArrayList<Integer>();
        }
        listAdapter = new ListAdapter(this, listDataHeader,asigid);
        
        // setting list adapter
        expListView.setAdapter(listAdapter);
        expListView.setOnItemClickListener( new OnItemClickListener(){

			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position,
					long id) {
				// TODO Auto-generated method stub
				Intent i = new Intent(getApplicationContext(), AlumnosActivity.class);
				int id1 = (int) id;
		        i.putExtra("Asignatura",id1);
		        startActivity(i);
		        

			}
        	
        });
        
        uid = usuario.get("uid");
    	if(!change){
    		new Asincrono().execute(userFunction);   
    	}
		
	}
	/*
	 * Preparing the list data
	 */
	private void prepareListData(JSONObject json) {
	    listDataHeader = new ArrayList<String>();
	    JSONArray asig = null;
	    JSONObject aux = null;
	    String aux1 = null;
	    try {
			 asig = json.getJSONArray("Asignaturas");
			 int i = asig.length();
			 for(int j=0;j<i;j++){
				 aux = asig.getJSONObject(j).getJSONObject("Datos");
				 aux1 = aux.getString("nombre")+"  grupo: " + aux.getString("grupo") + "   " + aux.getString("curso");
				 asigid.add(j,aux.getInt("id_asignatura") );
				 listDataHeader.add(aux1);
				 

			 }
		} catch (NullPointerException e) {
			// TODO Auto-generated catch block
			Toast.makeText(MenuPrincipalProfesor.this, "Sin Resultados",
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
	    // Adding child data
	    listDataHeader.add("Sin Datos");
	}
	
	@Override
    public boolean onCreateOptionsMenu(Menu menu){
    	getMenuInflater().inflate(R.menu.menu2,menu);
		return true;
    	
    }
    
    @Override public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.Desvincular2:
               desvincular();
               break;
        case R.id.Actualizar2:
        	new Asincrono().execute(userFunction);
        
        }
        return true; /** true -> consumimos el item, no se propaga*/
}
    
    private void desvincular(){
    	DataBaseHandler db = new DataBaseHandler(getApplicationContext());
    	
    	HashMap<String,String> userdata = db.getUserDetails();
    	
    	 //userFunction.logoutUser(getApplicationContext(), userdata.get("uid"));

         Intent login = new Intent(getApplicationContext(), MainActivity.class);

         login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

         startActivity(login);
         
         change = false;
         
         db.resetTables();

         // Closing dashboard screen

         finish();

    }
    
    private void refresh() {

    	change = true;

    	finish();

    	Intent myIntent = new Intent(this, MenuPrincipal.class);

    	startActivity(myIntent);

    	}
    
private class Asincrono extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MenuPrincipalProfesor.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setOnCancelListener(new OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialog) {
                    Asincrono.this.cancel(true);
                }
            });
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        @Override
    	protected JSONObject doInBackground(UserFunctions... userfunction) {
        	JSONObject json = userFunction.getSubjectsProf(uid);
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
        Toast.makeText(MenuPrincipalProfesor.this, "Tarea cancelada!",
            Toast.LENGTH_SHORT).show();
    }

	
	}
}
