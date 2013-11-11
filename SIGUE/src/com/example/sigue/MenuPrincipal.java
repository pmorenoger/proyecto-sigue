package com.example.sigue;

import android.app.Activity;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;

import android.os.Bundle;

import android.util.AttributeSet;
import android.view.Gravity;
import android.view.View;

import android.widget.Button;
import android.widget.LinearLayout.LayoutParams;
import android.widget.TabHost;
import android.widget.TabHost.OnTabChangeListener;
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
            customFont= makeTabIndicator("Mis Tokens");
            customFont.setTypeface(font);
            final TabHost tabs=(TabHost)findViewById(android.R.id.tabhost);            
        	tabs.setup();
        	 
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
        }
            /*customFont = (Button)findViewById(R.id.button1);
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

        }*/

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


    

}