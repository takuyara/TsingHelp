package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationManager;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import java.util.Timer;
import java.util.TimerTask;

import cz.msebera.android.httpclient.Header;

/*
用于实现拼单时间地点，即图1
 */
public class NewActivity extends AppCompatActivity {

    private Button startButton;
    private Timer timer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_new);
        startButton = (Button) findViewById(R.id.orderStart);
        startButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(NewActivity.this, OptionActivity.class);
                startActivity(intent);
            }
        });
        /*
        Timer timer = new Timer();
        AsyncHttpClient client = new AsyncHttpClient();
        timer.schedule(new TimerTask() {
            @Override
            public void run() {
                LocationManager locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
                if (ActivityCompat.checkSelfPermission(NewActivity.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(NewActivity.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                    return;
                }
                assert locationManager != null;
                Location location = locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
                double x = location.getAltitude(), y = location.getLongitude();
                RequestParams params = new RequestParams();
                params.put("u-id", Config.uid);
                params.put("u-token", Config.utoken);
                params.put("u-coord-x", Double.toString(x));
                params.put("u-coord-y", Double.toString(y));
                client.post(Config.myHost + "/update-u-coord", params, new AsyncHttpResponseHandler() {
                    @Override
                    public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                        Log.v("Success", "Success uploading message");
                    }
                    @Override
                    public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                        Log.e("Failure", "Failure uploading location");
                    }
                });
            }
        }, 0, 20000);
        */
    }
}