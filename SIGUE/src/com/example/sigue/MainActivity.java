package com.example.sigue;

import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Typeface;
import android.view.Gravity;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

import org.json.JSONException;
import org.json.JSONObject;
import android.app.Activity;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager.NameNotFoundException;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.example.sigue.library.DataBaseHandler;

import com.example.sigue.library.UserFunctions;
import com.google.android.gcm.GCMRegistrar;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.gcm.GoogleCloudMessaging;

public class MainActivity extends Activity {
	
	protected TextView customFont;
	TextView loginErrorMsg;
	TextView registerErrorMsg;
	JSONObject json;
	UserFunctions userFunction;
	String email;
	String contrasena;
	String regid;
	GoogleCloudMessaging gcm;
	Context context;

    // JSON Response node names
	
	private static final String PROPERTY_REG_ID = "registration_id";
	
	private static final String PROPERTY_APP_VERSION = "appVersion";
	
	private static final String PROPERTY_EXPIRATION_TIME = "onServerExpirationTimeMs";
	
	private static final String PROPERTY_USER = "user";
	
	private static final int PLAY_SERVICES_RESOLUTION_REQUEST = 0;
	
	private static final String TAG = "Debugeando";
	
	public static final String SENDER_ID = "376019289289";
	
	private static final long EXPIRATION_TIME_MS = 200000;

    private static String KEY_SUCCESS = "success";

    private static String KEY_ERROR = "error";

    private static String KEY_ERROR_MSG = "error_msg";

    private static String KEY_UID = "uid";

    private static String KEY_NAME = "name";
    
    private static String KEY_SURNAME = "surname";

    private static String KEY_EMAIL = "email";
    
    private static String KEY_PROF = "profesor";

    private static String KEY_CREATED_AT = "created_at";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		customFont = (TextView)findViewById(R.id.textView1);
		Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
		customFont.setTypeface(font);
		customFont = (Button)findViewById(R.id.button1);
		customFont.setTypeface(font);
		customFont = (TextView)findViewById(R.id.textView2);
		customFont.setTypeface(font);
		customFont = (TextView)findViewById(R.id.textView3);
		customFont.setTypeface(font);
		loginErrorMsg =(TextView) findViewById(R.id.textView3);
		final Button btnScan = (Button)findViewById(R.id.button1); 
		
			btnScan.setOnClickListener(new View.OnClickListener(){

				public void onClick(View v) {
				
				

					Intent intent = new Intent("com.example.sigue.SCAN");
					intent.putExtra("SCAN_MODE", "QR_CODE_MODE"); 
					startActivityForResult(intent, 0);
					


				}
	                

	        });
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent intent) {

	    if (requestCode == 0) {

	        if (resultCode == RESULT_OK) {

	            String contenido = intent.getStringExtra("SCAN_RESULT");
	            String formato = intent.getStringExtra("SCAN_RESULT_FORMAT");

	            // Hacer algo con los datos obtenidos.
	            try{
	            String [] parametros = contenido.split("#&");
	            	email = parametros[0];
	            	contrasena = parametros[1];
	            	userFunction = new UserFunctions();
					//new Asincrono1().execute(userFunction);
					
					 context = getApplicationContext();
					 
				        //Chequemos si está instalado Google Play Services
				        if(checkPlayServices())
				        {
				                gcm = GoogleCloudMessaging.getInstance(MainActivity.this);
				 
				                //Obtenemos el Registration ID guardado
				                regid = getRegistrationId(context);
				 
				                //Si no disponemos de Registration ID comenzamos el registro
				                //if (regid.equals("")) {
				                    TareaRegistroGCM tarea = new TareaRegistroGCM();
				                    tarea.execute(email);
				                //}
				        }
				        else
				        {
				               //Log.i(TAG, "No se ha encontrado Google Play Services.");
				            }
				                
	            }catch(ArrayIndexOutOfBoundsException e){
	            	Toast toast = Toast.makeText(this, "Codigo QR no válido", Toast.LENGTH_SHORT);
	                toast.show();
	            }catch(NullPointerException e){
	            	Toast toast = Toast.makeText(this, "Codigo QR no válido", Toast.LENGTH_SHORT);
	                toast.show();
	            }
	            
	  

	        } else if (resultCode == RESULT_CANCELED) {

	            // Si se cancelo la captura.

	        }

	    }

	}
	
	private boolean checkPlayServices() {
	    int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(this);
	    if (resultCode != ConnectionResult.SUCCESS)
	    {
	        if (GooglePlayServicesUtil.isUserRecoverableError(resultCode))
	        {
	            GooglePlayServicesUtil.getErrorDialog(resultCode, this,PLAY_SERVICES_RESOLUTION_REQUEST).show();
	        }
	        else
	        {
	            //Log.i(TAG, "Dispositivo no soportado.");
	            finish();
	        }
	        return false;
	    }
	    return true;
	}
	
	private void registerUser(Context context){
		  
        final String regId = GCMRegistrar.getRegistrationId(context);
        if (regId.equals("")) {
         GCMRegistrar.register(context, "376019289289"); 
         Log.v("GCM", "Registrado");
        } else {
            Log.v("GCM", "Ya registrado");
        } 
 }
	
	private String getRegistrationId(Context context)
	{
	    SharedPreferences prefs = getSharedPreferences(
	    MainActivity.class.getSimpleName(),
	        Context.MODE_PRIVATE);
	 
	    String registrationId = prefs.getString(PROPERTY_REG_ID, "");
	 
	    if (registrationId.length() == 0)
	    {
	        Log.d(TAG, "Registro GCM no encontrado.");
	        return "";
	    }
	 
	    String registeredUser =
	    prefs.getString(PROPERTY_USER, "user");
	 
	    int registeredVersion =
	    prefs.getInt(PROPERTY_APP_VERSION, Integer.MIN_VALUE);
	 
	    long expirationTime =
	        prefs.getLong(PROPERTY_EXPIRATION_TIME, -1);
	 
	    SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy HH:mm", Locale.getDefault());
	    String expirationDate = sdf.format(new Date(expirationTime));
	 
	    Log.d(TAG, "Registro GCM encontrado (usuario=" + registeredUser +
	    ", version=" + registeredVersion +
	    ", expira=" + expirationDate + ")");
	 
	    int currentVersion = getAppVersion(context);
	 
	    if (registeredVersion != currentVersion)
	    {
	        Log.d(TAG, "Nueva versión de la aplicación.");
	        return "";
	    }
	    else if (System.currentTimeMillis() > expirationTime)
	    {
	        Log.d(TAG, "Registro GCM expirado.");
	        return "";
	    }
	    else if (!email.equals(registeredUser))
	    {
	        Log.d(TAG, "Nuevo nombre de usuario.");
	        return "";
	    }
	 
	    return registrationId;
	}
	 
	private static int getAppVersion(Context context)
	{
	    try
	    {
	        PackageInfo packageInfo = context.getPackageManager()
	                .getPackageInfo(context.getPackageName(), 0);
	 
	        return packageInfo.versionCode;
	    }
	    catch (NameNotFoundException e)
	    {
	        throw new RuntimeException("Error al obtener versión: " + e);
	    }
	}
	
	private JSONObject registroServidor(String usuario,String contrasena, String regId)
	{
	    //boolean reg = false;
	 
	    JSONObject json = userFunction.loginUser(email, contrasena, regId);
	 
	    return json;
	}
	
	private void setRegistrationId(Context context, String user, String regId)
	{
	    SharedPreferences prefs = getSharedPreferences(MainActivity.class.getSimpleName(),Context.MODE_PRIVATE);
	 
	    int appVersion = getAppVersion(context);
	 
	    SharedPreferences.Editor editor = prefs.edit();
	    editor.putString(PROPERTY_USER, user);
	    editor.putString(PROPERTY_REG_ID, regId);
	    editor.putInt(PROPERTY_APP_VERSION, appVersion);
	    editor.putLong(PROPERTY_EXPIRATION_TIME,System.currentTimeMillis() + EXPIRATION_TIME_MS);
	 
	    editor.commit();
	}
	
	private class TareaRegistroGCM extends AsyncTask<String,Integer,JSONObject>
	{
		
		private final ProgressDialog dialog = new ProgressDialog(MainActivity.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setOnCancelListener(new OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialog) {
                    TareaRegistroGCM.this.cancel(true);
                }
            });
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        
	    @Override
	        protected JSONObject doInBackground(String... params)
	    {
	            String msg = "";
	            JSONObject registrado = null;
	 
	            try
	            {
	                if (gcm == null)
	                {
	                    gcm = GoogleCloudMessaging.getInstance(context);
	                }
	 
	                //Nos registramos en los servidores de GCM
	                regid = gcm.register(SENDER_ID);
	 
	                Log.d(TAG, "Registrado en GCM: registration_id=" + regid);
	 
	                //Nos registramos en nuestro servidor
	                registrado = registroServidor(email,contrasena, regid);
	 
	                //Guardamos los datos del registro
	                //if(registrado)
	                //{
	                    setRegistrationId(context, params[0], regid);
	                //}
	            }
	            catch (IOException ex)
	            {
	                Log.d(TAG, "Error registro en GCM:" + ex.getMessage());
	            }
	 
	            return registrado;
	        }
	    
	
        protected void onPostExecute(JSONObject json) {
		 // check for login response
		if (this.dialog.isShowing()) {
            this.dialog.dismiss();
        }

        try {

            if (json.getString(KEY_SUCCESS) != null) {

                loginErrorMsg.setText("");

                String res = json.getString(KEY_SUCCESS);

                if(Integer.parseInt(res) == 1){

                    // user successfully logged in

                    // Store user details in SQLite Database

                    DataBaseHandler db = new DataBaseHandler(getApplicationContext());

                    JSONObject json_user = json.getJSONObject("user");



                    // Clear all previous data in database

                    //userFunction.logoutUser(getApplicationContext());
                    boolean profe;
                    if (json_user.getString(KEY_PROF).equals("true")){
                    	profe = true;
                    }else{
                    	profe=false;
                    }

                    db.addUser(json_user.getString(KEY_NAME),json_user.getString(KEY_EMAIL), json_user.getString(KEY_UID), json_user.getString(KEY_SURNAME),profe);
                    //registerUser(getApplicationContext());
                    if (profe){
                    	// Launch Dashboard Screen

                        Intent dashboard = new Intent(getApplicationContext(), MenuPrincipalProfesor.class);



                        // Close all views before launching Dashboard

                        dashboard.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                        startActivity(dashboard);



                        // Close Login Screen

                        finish();
                        
                    	
                    }else{

                    // Launch Dashboard Screen

                    Intent dashboard = new Intent(getApplicationContext(), MenuPrincipal.class);



                    // Close all views before launching Dashboard

                    dashboard.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                    startActivity(dashboard);



                    // Close Login Screen

                    finish();
                    }

                }else{

                    // Error in login

                    loginErrorMsg.setText("Incorrect QR code");

                }

            }

        }catch (NullPointerException e) {
			// TODO Auto-generated catch block
			Toast.makeText(MainActivity.this, "Sin Resultados",
		            Toast.LENGTH_SHORT).show();
			e.printStackTrace();
		} catch (JSONException e) {

            e.printStackTrace();

        }
	    }

	@Override
    protected void onCancelled() {
        Toast.makeText(MainActivity.this, "Tarea cancelada!",
            Toast.LENGTH_SHORT).show();
    }
	
	}
	
	
private class Asincrono1 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MainActivity.this);
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
        	//JSONObject json = userFunction.loginUser(email, contrasena);
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

                loginErrorMsg.setText("");

                String res = json.getString(KEY_SUCCESS);

                if(Integer.parseInt(res) == 1){

                    // user successfully logged in

                    // Store user details in SQLite Database

                    DataBaseHandler db = new DataBaseHandler(getApplicationContext());

                    JSONObject json_user = json.getJSONObject("user");



                    // Clear all previous data in database

                    //userFunction.logoutUser(getApplicationContext());
                    boolean profe;
                    if (json_user.getString(KEY_PROF).equals("true")){
                    	profe = true;
                    }else{
                    	profe=false;
                    	registerUser(getApplicationContext());
                    }

                    db.addUser(json_user.getString(KEY_NAME),json_user.getString(KEY_EMAIL), json_user.getString(KEY_UID), json_user.getString(KEY_SURNAME),profe);
                    
                    if (profe){
                    	// Launch Dashboard Screen

                        Intent dashboard = new Intent(getApplicationContext(), MenuPrincipalProfesor.class);



                        // Close all views before launching Dashboard

                        dashboard.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                        startActivity(dashboard);



                        // Close Login Screen

                        finish();
                        
                    	
                    }else{

                    // Launch Dashboard Screen

                    Intent dashboard = new Intent(getApplicationContext(), MenuPrincipal.class);



                    // Close all views before launching Dashboard

                    dashboard.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                    startActivity(dashboard);



                    // Close Login Screen

                    finish();
                    }

                }else{

                    // Error in login

                    loginErrorMsg.setText("Incorrect QR code");

                }

            }

        }catch (NullPointerException e) {
			// TODO Auto-generated catch block
			Toast.makeText(MainActivity.this, "Sin Resultados",
		            Toast.LENGTH_SHORT).show();
			e.printStackTrace();
		} catch (JSONException e) {

            e.printStackTrace();

        }
	    }

	@Override
    protected void onCancelled() {
        Toast.makeText(MainActivity.this, "Tarea cancelada!",
            Toast.LENGTH_SHORT).show();
    }
	
	}
	

}
