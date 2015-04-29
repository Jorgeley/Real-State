package br.com.gpaengenharia.classes;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.widget.Toast;

import br.com.gpaengenharia.activities.AtvLogin;

/**
 * cria o serviço em segundo plano responsavel por sincronizar as tarefas
 */
public class getAlarme extends BroadcastReceiver {

    @Override
    public void onReceive(Context context, Intent intent) {
        if (AtvLogin.usuario != null)
            context.startService(new Intent(context, ServicoTarefas.class));
    }
}
