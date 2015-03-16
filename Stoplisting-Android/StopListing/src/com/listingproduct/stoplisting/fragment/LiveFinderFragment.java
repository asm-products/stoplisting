package com.listingproduct.stoplisting.fragment;

import com.listingproduct.stoplisting.R;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

public class LiveFinderFragment extends Fragment implements OnClickListener{

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View view = inflater.inflate(R.layout.livefinder_screen, container, false);

		// Take another photo button
		//Button addPhotoBtn = (Button) view.findViewById(R.id.addPhotoBtn);
		
		// Item Swank Rank
		TextView swankRankTv = (TextView) view.findViewById(R.id.swankRankTv);
		
		// Item Price
		TextView priceTv = (TextView) view.findViewById(R.id.priceTv);
		
		// Item Photo
		ImageView itemPhotoIv = (ImageView) view.findViewById(R.id.itemPhotoIv);
		
		// Item Name
		TextView itemNameTv = (TextView) view.findViewById(R.id.itemNameTv);
		
		// Item State
		TextView itemStateTv = (TextView) view.findViewById(R.id.itemStateTv);
		
		return view;
	}

	@Override
	public void onClick(View v) {
		
	}
}
