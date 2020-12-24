package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import java.security.MessageDigest;

import cz.msebera.android.httpclient.Header;

public class LoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

    }

    public void onClick(View view) {
        EditText editText;
        editText = (EditText)findViewById(R.id.loginUsername);
        String username = editText.getText().toString();
        editText = (EditText)findViewById(R.id.loginPassword);
        String password = editText.getText().toString();
        if (username.length() < 4 || password.length() < 4) {
            Toast.makeText(getApplicationContext(), "用户名太短", Toast.LENGTH_SHORT).show();
            return ;
        }
        String passwordHash;
        try {
            MessageDigest msgDig = MessageDigest.getInstance("SHA-256");
            msgDig.update(username.getBytes("UTF-8"));
            byte[] encoded = msgDig.digest();
            StringBuffer sBuffer = new StringBuffer();
            for (int i = 0; i < encoded.length; ++i) {
                String temp = Integer.toHexString(encoded[i] & 0xff);
                if (temp.length() == 1)  sBuffer.append("0");
                sBuffer.append(temp);
            }
            passwordHash = sBuffer.toString();
        } catch (Exception e) {
            e.printStackTrace();
            return ;
        }
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("u-id", username);
        params.put("u-pwd", passwordHash);
        client.post(Config.myHost + "/login", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                Config.uid = username;
                Config.utoken = new String(responseBody);
                Intent intent = new Intent(LoginActivity.this, NewActivity.class);
                startActivity(intent);
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                Toast.makeText(LoginActivity.this, "登陆或注册失败，用户名冲突或密码错误", Toast.LENGTH_LONG).show();
            }
        });
    }
}