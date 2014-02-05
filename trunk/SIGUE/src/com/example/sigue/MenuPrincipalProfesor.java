package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import android.app.Activity;
import android.graphics.Typeface;
import android.os.Bundle;
import android.widget.ExpandableListView;
import android.widget.TextView;


public class MenuPrincipalProfesor extends Activity  {
	protected TextView customFont;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	public static List<String> listDataHeader;
	public static HashMap<String, ArrayList<String>> listDataChild;
	private static boolean change = false;
	
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.activity_main_profesor);
		Typeface font = Typeface.createFromAsset(getAssets(), "ROADMOVIE TRIAL___.ttf");
		customFont = (TextView) findViewById(R.id.tituloprofe);
		customFont.setTypeface(font);
		//expListView = (ExpandableListView)findViewById(R.id.lvProf);
		
		/*poner esto en el xml, <ExpandableListView
	      android:id="@+id/lvProf"
	      android:layout_height="match_parent"
	      android:layout_width="match_parent"/>*/
		
		// preparing list data
        if(!change){	
        	//prepareListData();
        }
        //listAdapter = new ExpandableListAdapter(this, listDataHeader, listDataChild);
        
        // setting list adapter
        //expListView.setAdapter(listAdapter);
		
	}
}
