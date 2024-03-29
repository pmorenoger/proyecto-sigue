package com.example.sigue.library;



import java.util.ArrayList;

import java.util.List;



import org.apache.http.NameValuePair;

import org.apache.http.message.BasicNameValuePair;

import org.json.JSONObject;



import android.content.Context;



public class UserFunctions {



    private JSONParser jsonParser;



    // Testing in localhost using wamp or xampp

     //use http://10.0.2.2/ to connect to your localhost ie http://localhost/
    //http://ssii2013.e-ucm.es/Symfony/web/Android_api/

    //private static String loginURL = "http://ssii2013.e-ucm.es/Symfony/web/Android_api/";
    
    private static String loginURL = "http://ssii2013.e-ucm.es/Android_api/index.php/";
    
    //private static String loginURL = "http://192.168.1.122:8080/xampp/Android_api/";

    private static String registerURL = "http://ssii2013.e-ucm.es/Android_api/index.php/";
    
    //private static String registerURL = "http://192.168.1.122:8080/xampp/Android_api/";
    
    private static String qrURL = "http://ssii2013.e-ucm.es/Android_api/index.php/";
    
    //private static String qrURL = "http://192.168.1.122:8080/xampp/Android_api/";


    private static String login_tag = "login";

    private static String register_tag = "register";
    
    private static String qr_tag = "qr_register";
    
    private static String subject_tag = "subject_tag";
    
    private static String subject_tag_prof = "subject_tag_prof";
    
    private static String alumno_tag = "alumno_tag";
    
    private static String act_tag = "act_tag";
    
    private static String logout_tag = "logout";



    // constructor

    public UserFunctions(){

        jsonParser = new JSONParser();

    }



    /**

     * function make Login Request

     * @param email

     * @param password

     * */

    public JSONObject loginUser(String email, String password, String regID){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();

        params.add(new BasicNameValuePair("tag", login_tag));

        params.add(new BasicNameValuePair("email", email));

        params.add(new BasicNameValuePair("password", password));
        
        params.add(new BasicNameValuePair("gcmId", regID));

        JSONObject json = jsonParser.getJSONFromUrl(loginURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    
    public JSONObject qrRegister(String codigo, String user){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();

        params.add(new BasicNameValuePair("tag", qr_tag));

        params.add(new BasicNameValuePair("codigo", codigo));
        
        params.add(new BasicNameValuePair("user", user));

        JSONObject json = jsonParser.getJSONFromUrl(qrURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    
    public JSONObject getSubjects(String user){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        
        params.add(new BasicNameValuePair("tag",subject_tag));
        
        params.add(new BasicNameValuePair("user", user));

        JSONObject json = jsonParser.getJSONFromUrl(qrURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    
    public JSONObject getAlumnos(int id){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        
        params.add(new BasicNameValuePair("tag",alumno_tag));
        
        params.add(new BasicNameValuePair("user",Integer.toString(id)));

        JSONObject json = jsonParser.getJSONFromUrl(qrURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    
    public JSONObject getSubjectsProf(String user){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        
        params.add(new BasicNameValuePair("tag",subject_tag_prof));
        
        params.add(new BasicNameValuePair("user", user));

        JSONObject json = jsonParser.getJSONFromUrl(qrURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    
    



    /**

     * function make Login Request

     * @param name

     * @param email

     * @param password

     * */

    public JSONObject registerUser(String name, String email, String password){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();

        params.add(new BasicNameValuePair("tag", register_tag));

        params.add(new BasicNameValuePair("name", name));

        params.add(new BasicNameValuePair("email", email));

        params.add(new BasicNameValuePair("password", password));



        // getting JSON Object

        JSONObject json = jsonParser.getJSONFromUrl(registerURL, params);

        // return json

        return json;

    }



    /**

     * Function get Login status

     * */

    public boolean isUserLoggedIn(Context context){

        DataBaseHandler db = new DataBaseHandler(context);

        int count = db.getRowCount();

        if(count > 0){

            // user logged in

            return true;

        }

        return false;

    }


    public JSONObject modifyActivity(String nota, String observaciones,int id){

        // Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();

        params.add(new BasicNameValuePair("tag", act_tag));

        params.add(new BasicNameValuePair("nota", nota));

        params.add(new BasicNameValuePair("observaciones", observaciones));
        
        params.add(new BasicNameValuePair("id", Integer.toString(id)));

        JSONObject json = jsonParser.getJSONFromUrl(loginURL, params);

        

        // Log.e("JSON", json.toString());

        return json;

    }
    /**

     * Function to logout user

     * Reset Database

     * */

    public boolean logoutUser(Context context, String user){
    	
    	// Building Parameters

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        
        params.add(new BasicNameValuePair("tag",logout_tag));
        
        params.add(new BasicNameValuePair("user", user));

        JSONObject json = jsonParser.getJSONFromUrl(qrURL, params);

        // Log.e("JSON", json.toString());       

        return true;

    }



}