package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        String str = Config.getUrl();
        Toast.makeText(this, str, Toast.LENGTH_SHORT).show();
        if (str == null) Log.e("Conf", "Null");
    }
}