package com.example.sigue;

import java.util.ArrayList;
import java.util.HashMap;

import android.os.Bundle;
import android.os.Parcelable;
import android.app.Activity;
import android.view.Menu;
import android.widget.ExpandableListView;

public class ActividadesActivity extends Activity {

	private ActividadesLista lista_actividades;
	private ExpandableListView expListView;
	private static ArrayList<Actividad> listDataHeader;
	private static HashMap<Actividad, String> listDataChild;
	private ActividadListAdapter listAdapter;
	private float notaTokens;
	private float pesoTokens;
	private int user;
	private boolean profesor;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		String titulo;
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_actividades);
		Bundle contenedor=getIntent().getExtras();
		listDataHeader = new ArrayList<Actividad>();
	    lista_actividades=contenedor.getParcelable("array");
	    notaTokens = contenedor.getFloat("notaTokens");
	    pesoTokens = contenedor.getFloat("pesoTokens");
	    user = contenedor.getInt("usuario");
	    profesor = contenedor.getBoolean("profesor");
	    if(profesor){
	    titulo = contenedor.getString("nombre");
	    }else{
	    titulo=contenedor.getString("asignatura");
	    }
	    // get the listview
        expListView = (ExpandableListView) findViewById(R.id.lvExp3);
        prepareListData();
        listAdapter = new ActividadListAdapter(this, listDataHeader, user, profesor);    
        // setting list adapter
        expListView.setAdapter(listAdapter);
        
        this.setTitle(titulo);
	}

	private void prepareListData() {
		Actividad act;
		int i = lista_actividades.size();
		for (int j = 0; j<i;j++){
			act = lista_actividades.get(j);
			listDataHeader.add(act);		
		}
		
	}

	

}
