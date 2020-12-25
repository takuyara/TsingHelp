package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONObject;

import java.security.MessageDigest;

import cz.msebera.android.httpclient.Header;

public class LoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        Config.getUrl();
    }

    public void onClick(View view) {
        EditText editText;
        editText = (EditText)findViewById(R.id.loginUsername);
        String username = editText.getText().toString();
        editText = (EditText)findViewById(R.id.loginPassword);
        String password = editText.getText().toString();
        if (username.length() < 4 || password.length() < 4) {
            Toast.makeText(getApplicationContext(), "用户名或密码太短", Toast.LENGTH_SHORT).show();
            return ;
        }
        String passwordHash;
        try {
            MessageDigest msgDig = MessageDigest.getInstance("SHA-256");
            msgDig.update(password.getBytes("UTF-8"));
            byte[] encoded = msgDig.digest();
            StringBuffer sBuffer = new StringBuffer();
            for (int i = 0; i < encoded.length; ++i) {
                String temp = Integer.toHexString(encoded[i] & 0xff);
                if (temp.length() == 1) sBuffer.append("0");
                sBuffer.append(temp);
            }
            passwordHash = sBuffer.toString();
            Log.e("Hash", passwordHash);
        } catch (Exception e) {
            e.printStackTrace();
            return ;
        }
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("u-id", username);
        params.put("u-pwd", passwordHash);
        //Log.e("HST", Config.myHost == null ? "Fuck" : Config.myHost);
        //if (Config.myHost == null) Toast.makeText(getApplicationContext(), "Fuck null", Toast.LENGTH_SHORT).show();
        client.post(Config.myHost + "/ts-backend/login", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                String jsonString = new String(responseBody);
                try {
                    ///Log.e("JSON",  jsonString);
                    JSONObject jsonObject = new JSONObject(jsonString);
                    String msg = jsonObject.getString("msg");
                    Config.utoken = jsonObject.getString("u-token");
                    Log.e("msg=", msg);
                    Log.e("token", Config.utoken);
                    Log.e("response", jsonString);
                    if (!msg.equals("signup: ok") && !msg.equals("login: ok")) {
                        //Log.e("Not ok", msg);
                        Toast.makeText(LoginActivity.this, msg, Toast.LENGTH_SHORT).show();
                        return ;
                    } else {
                        //Log.e("TZLO", "tzl");
                        Config.uid = username;
                        Intent intent = new Intent(LoginActivity.this, NewActivity.class);
                        startActivity(intent);
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
                /*
                Log.e("Success", Integer.toString(statusCode));
                if (true) {
                    Config.uid = username;
                    Config.utoken = new String(responseBody);

                } else {
                    Toast.makeText(LoginActivity.this, "登陆或注册失败，用户名冲突或密码错误", Toast.LENGTH_LONG).show();
                }
                 */
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                Log.e("Failure", Integer.toString(statusCode));
                Toast.makeText(LoginActivity.this, Integer.toString(statusCode), Toast.LENGTH_SHORT).show();
            }
        });
    }
}