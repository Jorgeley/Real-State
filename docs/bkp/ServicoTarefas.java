package br.com.gpaengenharia.classes;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;
import android.util.Log;
import java.io.IOException;
import br.com.gpaengenharia.activities.AtvBase;
import br.com.gpaengenharia.activities.AtvLogin;
import br.com.gpaengenharia.classes.xmls.XmlTarefasPessoais;

public class ServicoTarefas extends Service implements Runnable{

    @Override
    public IBinder onBind(Intent intent) {
        return null; //sem interatividade com o serviço
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        //TODO nao sincronizar caso ja esteja sincronizado
        if (AtvLogin.usuario != null)
            new Thread(this).start();
        return super.onStartCommand(intent, flags, startId);
    }

    @Override
    public void run() {
        Log.i("serviço", "executando serviço");
        XmlTarefasPessoais xmlTarefasPessoais = new XmlTarefasPessoais(this);
        //baixa o XML de tarefas pessoais via werbservice e cria o arquivo localmente
        try {
            xmlTarefasPessoais.criaXmlProjetosPessoaisWebservice(AtvLogin.usuario.getId());
        } catch (IOException e) {
            e.printStackTrace();
        }
        AtvBase.atualizaListView = true;
    }
}
