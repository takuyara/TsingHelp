package com.example.tsinghelp;

import android.util.Log;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import cz.msebera.android.httpclient.Header;
import cz.msebera.android.httpclient.HttpResponse;
import cz.msebera.android.httpclient.HttpStatus;
import cz.msebera.android.httpclient.client.HttpClient;
import cz.msebera.android.httpclient.client.ResponseHandler;
import cz.msebera.android.httpclient.client.methods.HttpGet;
import cz.msebera.android.httpclient.impl.client.DefaultHttpClient;
import cz.msebera.android.httpclient.protocol.HTTP;
import cz.msebera.android.httpclient.util.EntityUtils;

public class Config {
    public static String gitURL = "https://takuyara.github.io/index.html";
    public static String myHost = null, uid, utoken;
    public static void getUrl() {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                HttpGet get = new HttpGet(gitURL);
                HttpClient client = new DefaultHttpClient();
                try {
                    HttpResponse response = client.execute(get);
                    if (response.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
                        myHost = EntityUtils.toString(response.getEntity(), HTTP.UTF_8);
                        Log.e("Host", myHost);
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });
        thread.start();
        try {
            thread.join();
        } catch (Exception e) {
            e.printStackTrace();
        }
        //return myHost;
        /*
        AsyncHttpClient client = new AsyncHttpClient();
        client.get(gitURL, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] response) {
                Config.myHost = new String(response);
                //Log.e("Suc", "Success");
                Log.e("host", Config.myHost);
                //Toast.makeText(null, string, Toast.LENGTH_SHORT).show();
            }
            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] errorResponse, Throwable e) {
                //Toast.makeText(getApplicationContext(), Integer.toString(statusCode), Toast.LENGTH_SHORT).show();
                Log.e("Fail", "Failure " + Integer.toString(statusCode));
            }
            @Override
            public void onRetry(int retryNo) {
                // called when request is retried
                Log.e("Re", "Retry");
                //Toast.makeText(DisplayMessageActivity.this, Integer.toString(retryNo), Toast.LENGTH_SHORT).show();
            }
        });

        while (myHost == null) {
            try {
                Log.e("Error", "Wait for check");
                Thread.sleep(1000);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
        try {
            if (myHost == null) Thread.sleep(10000);
        } catch (Exception e){
            e.printStackTrace();
        }
        Log.v("Host", Config.myHost);
         */
    }
    void login() {
        
    }
}
