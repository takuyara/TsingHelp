package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

public class OptionActivity2 extends AppCompatActivity {
    private ListView listView;
    private Button verifyButton;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_option2);
        Intent intent = getIntent();
        int sid = intent.getIntExtra("sid", 0);
        //Log.e("SID=", Integer.toString(sid));
        //Toast.makeText(this, Integer.toString(sid), Toast.LENGTH_SHORT).show();
        listView = (ListView)findViewById(R.id.itemList);
        List<String> listText = new ArrayList<String>();
        listText.add("三颗布丁");
        listText.add("五颗布丁");
        verifyButton = (Button) findViewById(R.id.verifyItem);
        verifyButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OptionActivity2.this, WaitActivity.class);
                startActivity(intent);
            }
        });
    }
}