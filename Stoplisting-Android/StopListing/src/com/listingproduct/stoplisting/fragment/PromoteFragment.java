package com.listingproduct.stoplisting.fragment;

import com.listingproduct.stoplisting.R;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class PromoteFragment extends Fragment implements OnClickListener{

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View view = inflater.inflate(R.layout.promote_screen, container, false);
		
		return view;
	}

	@Override
	public void onClick(View v) {

		switch (v.getId()) {
		default:
			break;
		}
	}
}
