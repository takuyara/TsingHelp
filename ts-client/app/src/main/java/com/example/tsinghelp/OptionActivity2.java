package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.ClipData;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import cz.msebera.android.httpclient.Header;

public class OptionActivity2 extends AppCompatActivity {
    private ListView listView;
    private Button verifyButton;
    private int sid;
    public String[] pid;
    public ItemAdapter itemAdapter;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_option2);
        Intent intent = getIntent();
        sid = intent.getIntExtra("sid", 0);
        //Log.e("SID=", Integer.toString(sid));
        //Toast.makeText(this, Integer.toString(sid), Toast.LENGTH_SHORT).show();
        listView = (ListView)findViewById(R.id.itemList);
        List<String> listText = new ArrayList<String>();
        /*
        listText.add("三颗布丁");
        listText.add("五颗布丁");
        */
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("u-id", Config.uid);
        params.put("u-token", Config.utoken);
        params.put("s-id", sid);
        client.post(Config.myHost + "/ts-backend/get-s", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                try {
                    Log.e("json:", new String(responseBody));
                    JSONObject jsonObject = new JSONObject(new String(responseBody));
                    String shopIcon = jsonObject.getString("s-icon");
                    String shopName = jsonObject.getString("s-name");
                    String deliv = jsonObject.getString("s-deliv");
                    String price = jsonObject.getString("s-price");
                    String rate = jsonObject.getString("s-rating");
                    JSONArray jsonArray = jsonObject.getJSONArray("s-ps");
                    TextView textView = (TextView)findViewById(R.id.shopName);
                    textView.setText(shopName);
                    textView = (TextView)findViewById(R.id.avgTime);
                    textView.setText("配送时间：" + deliv);
                    textView = (TextView)findViewById(R.id.avgCost);
                    textView.setText("人均：" + price + "元");
                    textView = (TextView)findViewById(R.id.avgScore);
                    textView.setText("评分：" + rate);
                    OptionActivity2.this.pid = new String[jsonArray.length()];
                    for (int i = 0; i < jsonArray.length(); ++i){
                        JSONObject jo = jsonArray.getJSONObject(i);
                        OptionActivity2.this.pid[i] = jo.getString("p_id");
                        String name = jo.getString("p_name");
                        String pprice = jo.getString("p_price");
                        listText.add("   " + name + "  价格：" + pprice + "元");
                    }

                    OptionActivity2.this.itemAdapter = new ItemAdapter(listText, OptionActivity2.this);
                    OptionActivity2.this.listView.setAdapter(OptionActivity2.this.itemAdapter);
                } catch (Exception e) {
                    Toast.makeText(OptionActivity2.this, new String(responseBody), Toast.LENGTH_SHORT).show();
                    e.printStackTrace();
                    return ;
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                Toast.makeText(OptionActivity2.this, "服务器忙，请稍后再试", Toast.LENGTH_LONG).show();
            }
        });
        listView.setAdapter(new ItemAdapter(listText, this));
    }
    public void onClick(View view) {
        //Log.e("Bfitt", "Before get intent");
        //listView = (ListView)findViewById(R.id.itemList);

        List<String> list = new ArrayList<String>();
        for (int i = 0; i < pid.length; ++i)
            if (Config.chmap.containsKey(i)) list.add(pid[i]);
        //Log.e("E", "Finish list add");
        String[] chosenPid = new String[list.size()];
        for (int i = 0; i < list.size(); ++i) chosenPid[i] = list.get(i);
        //Log.e("fsd", "Return");
        Intent intent = new Intent(OptionActivity2.this, WaitActivity.class);
        //if (intent == null) {
            //Log.e("E", "Error");
            //return ;
        //}
        //Log.e("IT", "Intent create");
        intent.putExtra("sid", sid);
        intent.putExtra("pid", chosenPid);
        //Log.e("BF", "Before start");
        startActivity(intent);
    }
}