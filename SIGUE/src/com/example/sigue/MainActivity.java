package com.example.sigue;

import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Typeface;
import android.view.Gravity;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.json.JSONException;
import org.json.JSONObject;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.example.sigue.library.DataBaseHandler;

import com.example.sigue.library.UserFunctions;

public class MainActivity extends Activity {
	protected TextView customFont;
	TextView loginErrorMsg;
	TextView registerErrorMsg;
	JSONObject json;
	UserFunctions userFunction;


    // JSON Response node names

    private static String KEY_SUCCESS = "success";

    private static String KEY_ERROR = "error";

    private static String KEY_ERROR_MSG = "error_msg";

    private static String KEY_UID = "uid";

    private static String KEY_NAME = "name";

    private static String KEY_EMAIL = "email";

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
		final Button btnScan = (Button)findViewById(R.id.button1); 
		
			btnScan.setOnClickListener(new View.OnClickListener(){

				public void onClick(View v) {
				
				

					/*Intent intent = new Intent("com.example.sigue.SCAN");
					intent.putExtra("SCAN_MODE", "QR_CODE_MODE"); 
					startActivityForResult(intent, 0);*/
					userFunction = new UserFunctions();
					new Asincrono1().execute(userFunction);


				}
	                

	        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_main, menu);
		return true;
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent intent) {

	    if (requestCode == 0) {

	        if (resultCode == RESULT_OK) {

	            String contenido = intent.getStringExtra("SCAN_RESULT");
	            String formato = intent.getStringExtra("SCAN_RESULT_FORMAT");

	            // Hacer algo con los datos obtenidos.
	            Toast toast = Toast.makeText(this, contenido, Toast.LENGTH_SHORT);
	            toast.setGravity(Gravity.CENTER_VERTICAL, 0, 0);
	            toast.show();        


	        } else if (resultCode == RESULT_CANCELED) {

	            // Si se cancelo la captura.

	        }

	    }

	}
	
	
	
private class Asincrono1 extends AsyncTask<UserFunctions, Void, JSONObject> {
    	
        private final ProgressDialog dialog = new ProgressDialog(MainActivity.this);
        private int mode;
        
        protected void onPreExecute() {
            this.dialog.setMessage("LOADING.................");
            this.dialog.setCancelable(true);
            this.dialog.show();
        }
        @Override
    	protected JSONObject doInBackground(UserFunctions... userfunction) {
        	JSONObject json = userFunction.loginUser("ramonnuche@ucm.es", "probando");
    		return json;
    	}
        

	@Override
        protected void onPostExecute(JSONObject json) {
		 // check for login response

        try {

            if (json.getString(KEY_SUCCESS) != null) {

                //loginErrorMsg.setText("");

                String res = json.getString(KEY_SUCCESS);

                if(Integer.parseInt(res) == 1){

                    // user successfully logged in

                    // Store user details in SQLite Database

                    DataBaseHandler db = new DataBaseHandler(getApplicationContext());

                    JSONObject json_user = json.getJSONObject("user");



                    // Clear all previous data in database

                    userFunction.logoutUser(getApplicationContext());

                    db.addUser(json_user.getString(KEY_NAME), json_user.getString(KEY_EMAIL), json.getString(KEY_UID), json_user.getString(KEY_CREATED_AT));                        



                    // Launch Dashboard Screen

                    Intent dashboard = new Intent(getApplicationContext(), MenuPrincipal.class);



                    // Close all views before launching Dashboard

                    dashboard.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                    startActivity(dashboard);



                    // Close Login Screen

                    finish();

                }else{

                    // Error in login

                   // loginErrorMsg.setText("Incorrect username/password");

                }

            }

        } catch (JSONException e) {

            e.printStackTrace();

        }
	    }

	
	}
	

}
