package com.example.mcjvapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.MultiFormatWriter;
import com.google.zxing.common.BitMatrix;

import com.journeyapps.barcodescanner.BarcodeEncoder;

public class QRActivity extends AppCompatActivity {

    ImageView imgQR;
    TextView txtNombre;
    Button btnCerrar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_qractivity);

        imgQR = findViewById(R.id.imgQR);

        txtNombre = findViewById(R.id.txtNombre);

        btnCerrar = findViewById(R.id.btnCerrar);

        String nombre =
                getIntent().getStringExtra("nombre");

        String token =
                getIntent().getStringExtra("token");

        txtNombre.setText(nombre);

        generarQR(token);

        btnCerrar.setOnClickListener(v -> {

            Intent i =
                    new Intent(
                            QRActivity.this,
                            MainActivity.class);

            startActivity(i);

            finish();
        });
    }

    private void generarQR(String token){

        try{

            MultiFormatWriter writer =
                    new MultiFormatWriter();

            BitMatrix matrix =
                    writer.encode(

                            token,

                            BarcodeFormat.QR_CODE,

                            700,

                            700);

            BarcodeEncoder encoder =
                    new BarcodeEncoder();

            Bitmap bitmap =
                    encoder.createBitmap(matrix);

            imgQR.setImageBitmap(bitmap);

        }catch (Exception e){

            e.printStackTrace();
        }
    }
}