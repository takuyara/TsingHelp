package com.example.tsinghelp;

import android.icu.util.Calendar;
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
    public static String gitURL = "https://takuyara.github.io/index.html?";
    public static String myHost = null, uid, utoken;
    public static void getUrl() {
        Calendar calendar = Calendar.getInstance();
        String url = gitURL + Long.toString(calendar.getTimeInMillis());
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                HttpGet get = new HttpGet(url);
                HttpClient client = new DefaultHttpClient();
                try {
                    HttpResponse response = client.execute(get);
                    if (response.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
                        Config.myHost = "http://" + EntityUtils.toString(response.getEntity(), HTTP.UTF_8);
                        //Log.e("Host", myHost);
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
    }
    void login() {

    }
}
