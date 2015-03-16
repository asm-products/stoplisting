package com.listingproduct.stoplisting.fragment;

import com.listingproduct.stoplisting.R;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

public class FragmentTemplate extends Fragment implements OnClickListener{

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View view = inflater.inflate(R.layout.swank_result_screen0, container, false);
		return view;
	}

	@Override
	public void onClick(View v) {
		
	}
}
