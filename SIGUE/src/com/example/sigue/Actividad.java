package com.example.sigue;

public class Actividad {
private String nombre;
private String descripcion;
private String nota;
private String peso;
private String observaciones;
private int id;

public Actividad(){}
public Actividad(String nom, String des, String not, String peso,String obs, int id){
	this.setNombre(nom);
	this.setDescripcion(des);
	this.setNota(not);
	this.setPeso(peso);
	this.setObservaciones(obs);
	this.setId(id);
	
}

public String getNombre() {
	return nombre;
}

public void setNombre(String nombre) {
	this.nombre = nombre;
}

public String getDescripcion() {
	return descripcion;
}

public void setDescripcion(String descripcion) {
	this.descripcion = descripcion;
}

public String getNota() {
	return nota;
}

public void setNota(String nota) {
	this.nota = nota;
}

public String getPeso() {
	return peso;
}

public void setPeso(String peso) {
	this.peso = peso;
}

public int getId() {
	return id;
}

public void setId(int id) {
	this.id = id;
}

public String getObservaciones() {
	return observaciones;
}

public void setObservaciones(String observaciones) {
	this.observaciones = observaciones;
}

@Override
public boolean equals(Object o) {
  if (o instanceof Actividad) {
    Actividad p = (Actividad)o;
    return this.getId()==p.getId();
  } else {
    return false;
  }
}

@Override
public int hashCode() {
  return this.id * this.nombre.length();
}
}
