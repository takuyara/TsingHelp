package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

/*
用于实现拼单时间地点，即图1
 */
public class NewActivity extends AppCompatActivity{

    private Button startButton;
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
    }
}