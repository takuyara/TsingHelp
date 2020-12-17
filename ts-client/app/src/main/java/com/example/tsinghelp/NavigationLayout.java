package com.example.tsinghelp;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
/*
此类用于实现自定义控件-导航栏
 */
public class NavigationLayout extends LinearLayout {
    public Button newButton, ordersButton, infoButton;
    public NavigationLayout(Context context, AttributeSet attrs){
        super(context,attrs);
        LayoutInflater.from(context).inflate(R.layout.navigation, this);
        newButton = (Button) findViewById(R.id.newButton);
        ordersButton = (Button) findViewById(R.id.ordersButton);
        infoButton = (Button) findViewById(R.id.infoButton);
        newButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                //写“新的拼单”的跳转逻辑
                Intent intent = new Intent((Activity) getContext(), NewActivity.class);
                getContext().startActivity(intent);
            }
        });
        ordersButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                //写“拼单记录”的跳转逻辑
                Intent intent = new Intent((Activity) getContext(), OrdersActivity.class);
                getContext().startActivity(intent);
            }
        });
        infoButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                //写“我的”的跳转逻辑
                Intent intent = new Intent((Activity) getContext(), InfoActivity.class);
                getContext().startActivity(intent);
            }
        });
    }
}
