package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.RequestParams;

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
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        
    }
}