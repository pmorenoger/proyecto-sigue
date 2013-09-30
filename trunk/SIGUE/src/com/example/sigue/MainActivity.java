package com.example.sigue;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.view.Gravity;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

public class MainActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		final Button btnScan = (Button)findViewById(R.id.button1); 
		
			btnScan.setOnClickListener(new View.OnClickListener(){

				public void onClick(View v) {
				
				

					Intent intent = new Intent("com.example.sigue.SCAN");
					intent.putExtra("SCAN_MODE", "QR_CODE_MODE"); 
					startActivityForResult(intent, 0);
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

}
