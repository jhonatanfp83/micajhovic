package com.example.mcjvapp;

import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class ResultadoActivity
        extends AppCompatActivity {

    TextView txtEstado,
            txtNombre,
            txtTipo;

    ImageView icono;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        setContentView(
                R.layout.activity_resultado);

        txtEstado =
                findViewById(R.id.txtEstado);

        txtNombre =
                findViewById(R.id.txtNombre);

        txtTipo =
                findViewById(R.id.txtTipo);

        icono =
                findViewById(R.id.icono);

        String estado =
                getIntent().getStringExtra(
                        "estado");

        if(estado.equals("APROBADO")){

            String nombre =
                    getIntent().getStringExtra(
                            "nombre");

            String tipo =
                    getIntent().getStringExtra(
                            "tipo");

            txtEstado.setText(
                    "ACCESO APROBADO");

            txtNombre.setText(
                    nombre);

            txtTipo.setText(
                    tipo);

            txtEstado.setTextColor(
                    Color.GREEN);

            icono.setImageResource(
                    android.R.drawable.checkbox_on_background);

        }else{

            txtEstado.setText(
                    "ACCESO DENEGADO");

            txtNombre.setText(
                    "QR YA UTILIZADO");

            txtTipo.setText("");

            txtEstado.setTextColor(
                    Color.RED);

            icono.setImageResource(
                    android.R.drawable.ic_delete);
        }

        new Handler().postDelayed(() -> {

            finish();

        },3000);
    }
}