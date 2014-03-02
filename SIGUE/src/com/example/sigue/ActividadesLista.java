package com.example.sigue;

import java.util.ArrayList;

import android.os.Parcel;
import android.os.Parcelable;

public class ActividadesLista extends ArrayList<Actividad> implements Parcelable {
	

	public ActividadesLista(){}
	public ActividadesLista(Parcel in){
		readfromParcel(in);
	}
	private void readfromParcel(Parcel in) {
		this.clear();
		int size = in.readInt();
		//Leemos el tamaño del array int size = in.readInt();
		for (int i = 0; i < size; i++)
		{
		//el orden de los atributos SI importa
		Actividad act = new Actividad();
		act.setNombre(in.readString());
		act.setDescripcion(in.readString());
		act.setNota(in.readString());
		act.setPeso(in.readString());
		act.setObservaciones(in.readString());
		act.setId(in.readInt());
		this.add(act);
		}

		
	}
	@Override
	public int describeContents() {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public void writeToParcel(Parcel dest, int arg1) {
		int size = this.size();
		dest.writeInt(size);
		for (int i = 0; i < size; i++)
		{
		Actividad act = this.get(i);
		dest.writeString(act.getNombre());
		dest.writeString(act.getDescripcion());
		dest.writeString(act.getNota());
		dest.writeString(act.getPeso());
		dest.writeString(act.getObservaciones());
		dest.writeInt(act.getId());
		}
		}
	public static final Parcelable.Creator CREATOR = new Parcelable.Creator()
	{
	public ActividadesLista createFromParcel(Parcel in)
	{
	return new ActividadesLista(in);
	}
	public Object[] newArray(int arg0)
	{
	return null;
	}
	};

}
