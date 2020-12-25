package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

public class WaitActivity extends AppCompatActivity {

    int sid;
    String[] pid;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wait);
        Intent intent = getIntent();
        sid = intent.getIntExtra("sid", 0);
        pid = intent.getStringArrayExtra("pid");
        Toast.makeText(this, Integer.toString(pid.length), Toast.LENGTH_SHORT).show();
    }
}