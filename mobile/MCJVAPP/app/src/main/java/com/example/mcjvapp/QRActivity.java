package com.example.mcjvapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.MultiFormatWriter;
import com.google.zxing.common.BitMatrix;

import com.journeyapps.barcodescanner.BarcodeEncoder;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class QRActivity extends AppCompatActivity {

    /* Cada cuánto se pregunta al servidor si el QR cambió (BUG-14) */
    private static final long INTERVALO_MS = 4000;

    ImageView imgQR;
    TextView txtNombre;
    Button btnCerrar;

    private String correo;
    private String password;
    private String tokenActual;

    private RequestQueue queue;
    private final Handler handler = new Handler(Looper.getMainLooper());
    private boolean consultando = false;

    private final Runnable refrescar = new Runnable() {
        @Override
        public void run() {
            consultarToken();
            handler.postDelayed(this, INTERVALO_MS);
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_qractivity);

        imgQR = findViewById(R.id.imgQR);

        txtNombre = findViewById(R.id.txtNombre);

        btnCerrar = findViewById(R.id.btnCerrar);

        String nombre =
                getIntent().getStringExtra("nombre");

        tokenActual =
                getIntent().getStringExtra("token");

        correo =
                getIntent().getStringExtra("correo");

        password =
                getIntent().getStringExtra("password");

        queue = Volley.newRequestQueue(this);

        txtNombre.setText(nombre);

        generarQR(tokenActual);

        btnCerrar.setOnClickListener(v -> {

            Intent i =
                    new Intent(
                            QRActivity.this,
                            MainActivity.class);

            startActivity(i);

            finish();
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (correo != null && password != null) {
            handler.postDelayed(refrescar, INTERVALO_MS);
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        handler.removeCallbacks(refrescar);
    }

    /* Pide el token vigente; si el sistema de escaneo ya usó el QR, muestra el nuevo */
    private void consultarToken(){

        if (consultando) {
            return;
        }
        consultando = true;

        StringRequest request =
                new StringRequest(

                        Request.Method.POST,

                        getString(R.string.api_base_url) + "token_actual.php",

                        response -> {

                            consultando = false;

                            try{

                                JSONObject json =
                                        new JSONObject(response);

                                if(json.getString("status").equals("success")){

                                    String nuevo = json.getString("token");

                                    if(!nuevo.equals(tokenActual)){

                                        tokenActual = nuevo;

                                        generarQR(nuevo);

                                        Toast.makeText(
                                                this,
                                                "QR actualizado",
                                                Toast.LENGTH_SHORT).show();
                                    }
                                }

                            }catch (Exception e){

                                e.printStackTrace();
                            }
                        },

                        error -> consultando = false

                ){

                    @Override
                    protected Map<String, String> getParams(){

                        Map<String,String> params =
                                new HashMap<>();

                        params.put("correo",correo);

                        params.put("password",password);

                        return params;
                    }
                };

        /* Esperar hasta 60 s: el servidor gratis de Render puede estar dormido */
        request.setRetryPolicy(
                new DefaultRetryPolicy(60000, 0, 1f));

        queue.add(request);
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
