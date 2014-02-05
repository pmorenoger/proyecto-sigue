package com.example.sigue.library;



import java.util.HashMap;



import android.content.ContentValues;

import android.content.Context;

import android.database.Cursor;

import android.database.sqlite.SQLiteDatabase;

import android.database.sqlite.SQLiteOpenHelper;



public class DataBaseHandler extends SQLiteOpenHelper {



    // All Static variables

    // Database Version

    private static final int DATABASE_VERSION = 1;



    // Database Name

    private static final String DATABASE_NAME = "android_api";



    // Login table name

    private static final String TABLE_LOGIN = "login";
    
    private static final String TABLE_TOKEN = "tokens";
    
    private static final String TABLE_ASIG = "asignaturas";



    // Login Table Columns names

    private static final String KEY_ID = "id";

    private static final String KEY_NAME = "name";
    
    private static final String KEY_SURNAME = "surname";

    private static final String KEY_EMAIL = "email";
    
    private static final String KEY_PROF = "prof";

    private static final String KEY_UID = "uid";

    private static final String KEY_CREATED_AT = "created_at";
    
    private static final String KEY_CODID = "id_cod";
    
    private static final String KEY_CODIGO = "codigo";
    
    private static final String KEY_CODASIG = "cod_asig";
    
    private static final String KEY_CURSO = "curso";
    
    private static final String KEY_GRUPO = "grupo";
    
    private static final String KEY_NOMASIG = "asignatura";



    public DataBaseHandler(Context context) {

        super(context, DATABASE_NAME, null, DATABASE_VERSION);

    }



    // Creating Tables

    @Override

    public void onCreate(SQLiteDatabase db) {

        String CREATE_LOGIN_TABLE = "CREATE TABLE " + TABLE_LOGIN + "("

                + KEY_ID + " INTEGER PRIMARY KEY,"

                + KEY_NAME + " TEXT,"
                
                + KEY_SURNAME + " TEXT,"

                + KEY_EMAIL + " TEXT UNIQUE,"
                
                + KEY_PROF + " INTEGER,"

                + KEY_UID + " TEXT" + ")";
        
        String CREATE_TOKEN_TABLE = "CREATE TABLE " + TABLE_TOKEN + "("
        		
        		+ KEY_CODID + " INTEGER PRIMARY KEY,"
        		
        		+ KEY_CODIGO + " TEXT,"
        		
        		+ KEY_CODASIG + " INTEGER)";
        
        String CREATE_ASIG_TABLE = "CREATE TABLE " + TABLE_ASIG + "("
        		
        		+ KEY_CODASIG + " INTEGER PRIMARY KEY,"
        		
        		+ KEY_CURSO + " TEXT,"
        		
        		+ KEY_GRUPO + " TEXT,"
 
        		+KEY_NOMASIG + "TEXT)";

        db.execSQL(CREATE_LOGIN_TABLE);
        db.execSQL(CREATE_TOKEN_TABLE);
        db.execSQL(CREATE_ASIG_TABLE);

    }



    // Upgrading database

    @Override

    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {

        // Drop older table if existed

        db.execSQL("DROP TABLE IF EXISTS " + TABLE_LOGIN);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_TOKEN);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_ASIG);



        // Create tables again

        onCreate(db);

    }



    /**

     * Storing user details in database

     * */

    public void addUser(String name, String email, String uid, String surname, boolean prof) {

        SQLiteDatabase db = this.getWritableDatabase();



        ContentValues values = new ContentValues();

        values.put(KEY_NAME, name); // Name

        values.put(KEY_EMAIL, email); // Email

        values.put(KEY_UID, uid); // UID

        values.put(KEY_SURNAME, surname); // Surname
        
        values.put(KEY_PROF, prof);



        // Inserting Row

        db.insert(TABLE_LOGIN, null, values);

        db.close(); // Closing database connection

    }



    /**

     * Getting user data from database

     * */

    public HashMap<String, String> getUserDetails(){

        HashMap<String,String> user = new HashMap<String,String>();

        String selectQuery = "SELECT  * FROM " + TABLE_LOGIN;



        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(selectQuery, null);

        // Move to first row

        cursor.moveToFirst();

        if(cursor.getCount() > 0){

            user.put("name", cursor.getString(1));
            
            user.put("surname", cursor.getString(2));

            user.put("email", cursor.getString(3));
            
            user.put("profesor", cursor.getString(4));

            user.put("uid", cursor.getString(5));

        }

        cursor.close();

        db.close();

        // return user

        return user;

    }



    /**

     * Getting user login status

     * return true if rows are there in table

     * */

    public int getRowCount() {

        String countQuery = "SELECT  * FROM " + TABLE_LOGIN;

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(countQuery, null);

        int rowCount = cursor.getCount();

        db.close();

        cursor.close();



        // return row count

        return rowCount;

    }



    /**

     * Re crate database

     * Delete all tables and create them again

     * */

    public void resetTables(){

        SQLiteDatabase db = this.getWritableDatabase();

        // Delete All Rows

        db.delete(TABLE_LOGIN, null, null);
        db.delete(TABLE_TOKEN, null, null);
        db.delete(TABLE_ASIG, null, null);

        db.close();

    }



}