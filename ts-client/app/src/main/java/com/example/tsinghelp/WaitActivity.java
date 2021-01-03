package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONObject;

import java.util.Calendar;
import java.util.Timer;
import java.util.TimerTask;

import cz.msebera.android.httpclient.Header;

public class WaitActivity extends AppCompatActivity {

    int sid;
    String[] pid;
    long startTime;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wait);
        /*
        Intent intent = getIntent();
        sid = intent.getIntExtra("sid", 0);
        pid = intent.getStringArrayExtra("pid");
         */
        //Toast.makeText(this, Integer.toString(pid.length), Toast.LENGTH_SHORT).show();
        startTime = Calendar.getInstance().getTimeInMillis();
        Config.gotPartner = false;
        for (; ; ) {
            long curTime = Calendar.getInstance().getTimeInMillis();
            if (curTime - startTime > 10000) break;
            TextView textView = (TextView)findViewById(R.id.timeCost);
            if (textView == null){ Log.e("NULLVEIW", "NUOLL"); return ; }
            int t = (int) ((curTime - startTime) / 1000);
            textView.setText("等待时间：" + Integer.toString(t) + "秒");
            textView = (TextView)findViewById(R.id.peopleNearby);
            t = 5 + (int)(curTime % 10);
            textView.setText("附近人数：" + Integer.toString(t));
        }
        Intent intent = new Intent(this, PayActivity.class);
        startActivity(intent);
    }
    private void qq() {

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("u-id", Config.uid);
        params.put("u-token", Config.utoken);
        params.put("s-id", sid);
        params.put("o-p-ids", pid);
        params.put("o-p-n", pid.length);
        Log.e("url: ", Config.myHost + "/ts-backend/new-o");
        client.post(Config.myHost + "/ts-backend/new-o", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                try {
                    //JSONObject jsonObject = new JSONObject(new String(responseBody));
                    //Config.oid = jsonObject.getInt("o-id");
                    startTime = Calendar.getInstance().getTimeInMillis();
                    Config.gotPartner = false;
                    Timer timer = new Timer();
                    timer.schedule(new TimerTask() {
                        @Override
                        public void run() {
                            long curTime = Calendar.getInstance().getTimeInMillis();
                            if (curTime - startTime > 30000) {
                                Config.gotPartner = true;
                                Toast.makeText(WaitActivity.this, "已寻找到拼单合伙人", Toast.LENGTH_LONG).show();
                                Intent intent = new Intent(WaitActivity.this, PayActivity.class);
                                startActivity(intent);
                            } else {
                                TextView textView = (TextView)findViewById(R.id.timeCost);
                                if (textView == null){ Log.e("NULLVEIW", "NUOLL"); return ; }

                                int t = (int) ((curTime - startTime) / 1000);
                                textView.setText("等待时间：" + Integer.toString(t) + "秒");
                                textView = (TextView)findViewById(R.id.peopleNearby);
                                t = 5 + (int)(curTime % 10);
                                textView.setText("附近人数：" + Integer.toString(t));
                            }
                        }
                    }, 0, 1000);

                    AsyncHttpClient client = new AsyncHttpClient();
                    RequestParams params = new RequestParams();

                } catch (Exception e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                Toast.makeText(WaitActivity.this, "服务器忙，请稍后再试: " + Integer.toString(statusCode), Toast.LENGTH_LONG).show();
                Log.e("ERROR", Integer.toString(statusCode));
            }
        });
    }
}
