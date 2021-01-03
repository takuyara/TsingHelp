package com.example.tsinghelp;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.TextView;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ItemAdapter extends BaseAdapter {
    private List<String> listText;
    private Context context;
    public Map<Integer, Boolean> map = new HashMap<>();
    public ItemAdapter(List<String> listText, Context context) {
        this.listText = listText;
        this.context = context;
    }
    @Override
    public int getCount() {
        return listText.size();
    }
    @Override
    public Object getItem(int position) {
        return listText.get(position);
    }
    @Override
    public long getItemId(int position) {
        return position;
    }
    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view;
        if (convertView == null) {
            view = View.inflate(context, R.layout.list_view_item, null);
        } else {
            view = convertView;
        }
        TextView radioText = (TextView)view.findViewById(R.id.tv_check_text);
        radioText.setText(listText.get(position));
        final CheckBox checkBox =(CheckBox)view.findViewById(R.id.rb_check_button);
        checkBox.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (checkBox.isChecked()) {
                    map.put(position, true);
                    Config.chmap.put(position, true);
                } else {
                    map.remove(position);
                    Config.chmap.remove(position);
                }
            }
        });
        checkBox.setSelected(map != null && map.containsKey(position));
        return view;
    }
}
