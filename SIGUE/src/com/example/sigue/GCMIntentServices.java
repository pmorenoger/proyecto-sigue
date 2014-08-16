package com.example.sigue;

import com.example.sigue.GCMBroadcastReceiver;
import com.google.android.gms.gcm.GoogleCloudMessaging;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;

public class GCMIntentServices extends IntentService {
	
	 private static final int NOTIF_ALERTA_ID = 1;
	 
	    public GCMIntentServices() {
	            super("GCMIntentService");
	        }
	 
	    @Override
	        protected void onHandleIntent(Intent intent)
	    {
	            GoogleCloudMessaging gcm = GoogleCloudMessaging.getInstance(this);
	 
	            String messageType = gcm.getMessageType(intent);
	            Bundle extras = intent.getExtras();
	 
	            if (!extras.isEmpty())
	            {
	                    if (GoogleCloudMessaging.MESSAGE_TYPE_MESSAGE.equals(messageType))
	                    {
	                        mostrarNotification(extras.getString("collapse_key"));
	                    }
	            }
	 
	            GCMBroadcastReceiver.completeWakefulIntent(intent);
	        }
	 
	    private void mostrarNotification(String msg)
	    {
	        NotificationManager mNotificationManager =
	                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
	 
	        NotificationCompat.Builder mBuilder =
	            new NotificationCompat.Builder(this)
	                .setSmallIcon(R.drawable.ic_launcher)
	                .setContentTitle("Nueva calificación.")
	                .setContentText(msg);
	        mBuilder.setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION));
	        mBuilder.setAutoCancel(true);
	        mBuilder.setVibrate(new long[] { 50, 50, 100, 50, 50 });
	        mBuilder.setDefaults(Notification.DEFAULT_LIGHTS);
	        Intent notIntent =  new Intent(this, MenuPrincipal.class);
	        PendingIntent contIntent = PendingIntent.getActivity(
	                this, 0, notIntent, 0);
	 
	        mBuilder.setContentIntent(contIntent);
	 
	        mNotificationManager.notify(msg,NOTIF_ALERTA_ID, mBuilder.build());
	        }
}
