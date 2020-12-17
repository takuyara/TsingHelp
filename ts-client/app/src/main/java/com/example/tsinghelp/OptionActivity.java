package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import java.security.cert.PKIXRevocationChecker;

/*
用于实现选择拼单商家
 */
public class OptionActivity extends AppCompatActivity {
    private Button verifyButton;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_option);
        verifyButton = (Button) findViewById(R.id.verifyShop);
        verifyButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(OptionActivity.this, OptionActivity2.class);
                startActivity(intent);
            }
        });
    }
}