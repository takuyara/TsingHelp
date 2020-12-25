package com.example.tsinghelp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONObject;

import java.security.cert.PKIXRevocationChecker;

import cz.msebera.android.httpclient.Header;

/*
用于实现选择拼单商家
 */
public class OptionActivity extends AppCompatActivity {
    private Button verifyButton;
    public int[] sid = null;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_option);
        //String[] data = {"伏见桃山\n三个补丁、五个补丁", "茶百道\n没味儿豆奶", "定之镜堂\n舍似茶"};
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("u-id", Config.uid);
        params.put("u-token", Config.utoken);
        if (Config.utoken != null)
            Log.e("user token: ", Config.utoken);
        params.put("s-from", "1");
        params.put("s-to", "1000");
        //Log.e("HOST:", Config.myHost);
        client.post(Config.myHost + "/ts-backend/get-ss", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                String[] data = null;
                try {
                    Log.e("Rsponse", new String(responseBody));
                    JSONObject jsonObject = new JSONObject(new String(responseBody));
                    JSONArray jsonArray = jsonObject.getJSONArray("ss");
                    data = new String[jsonArray.length()];
                    OptionActivity.this.sid = new int[jsonArray.length()];
                    for (int i = 0; i < jsonArray.length(); ++i) {
                        JSONObject jo = jsonArray.getJSONObject(i);
                        String name = jo.getString("s-name");
                        double deliv = jo.getDouble("s-deliv");
                        double price = jo.getDouble("s-price");
                        double rate = jo.getDouble("s-rating");
                        OptionActivity.this.sid[i] = jo.getInt("s-id");
                        data[i] = name + "  配送时间：" + Double.toString(deliv)
                                + "\n人均价格：" + Double.toString(price) + "  评分：" + Double.toString(rate);
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    return ;
                }
                ArrayAdapter<String> adapter = new ArrayAdapter<String>(OptionActivity.this, android.R.layout.simple_list_item_1, data);
                ListView listView = findViewById(R.id.shopList);
                listView.setAdapter(adapter);
                listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                    @Override
                    public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                        Intent intent = new Intent(OptionActivity.this, OptionActivity2.class);
                        intent.putExtra("sid", OptionActivity.this.sid[position]);
                        startActivity(intent);
                    }
                });
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                Toast.makeText(OptionActivity.this, "服务器忙，请稍后再试", Toast.LENGTH_SHORT).show();
                Log.e("Statuscode=", Integer.toString(statusCode));
            }
        });
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