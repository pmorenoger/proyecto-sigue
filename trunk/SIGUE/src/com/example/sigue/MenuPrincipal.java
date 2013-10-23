package com.example.sigue;

import android.app.Activity;

import android.content.Intent;
import android.graphics.Typeface;

import android.os.Bundle;

import android.view.View;

import android.widget.Button;
import android.widget.TextView;



import com.example.sigue.library.UserFunctions;



public class MenuPrincipal extends Activity {
	protected TextView customFont;

    UserFunctions userFunctions;

    Button btnLogout;

    @Override

    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);



        /**

         * Dashboard Screen for the application

         * */

        // Check login status in database

        userFunctions = new UserFunctions();

        if(userFunctions.isUserLoggedIn(getApplicationContext())){

       // user already logged in show databoard

            setContentView(R.layout.activity_menu_principal);
            Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
            customFont = (Button)findViewById(R.id.button1);
            customFont.setTypeface(font);
            customFont = (Button)findViewById(R.id.button2);
            customFont.setTypeface(font);
            customFont = (Button)findViewById(R.id.button3);
            customFont.setTypeface(font);
            customFont = (Button)findViewById(R.id.button4);
            customFont.setTypeface(font);

            btnLogout = (Button) findViewById(R.id.button4);



            btnLogout.setOnClickListener(new View.OnClickListener() {



                public void onClick(View arg0) {

                    // TODO Auto-generated method stub

                    userFunctions.logoutUser(getApplicationContext());

                    Intent login = new Intent(getApplicationContext(), MainActivity.class);

                    login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

                    startActivity(login);

                    // Closing dashboard screen

                    finish();

                }

            });



        }else{

            // user is not logged in show login screen

            Intent login = new Intent(getApplicationContext(), MainActivity.class);

            login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            startActivity(login);

            // Closing dashboard screen

            finish();

        }

    }

}