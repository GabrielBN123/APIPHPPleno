init_minio:
#!/bin/sh

echo "Aguardando MinIO iniciar..."
COUNTER=0
MAX_RETRIES=20

while ! mc alias set myminio http://minio:9000 minioadmin minioadmin; do
  echo "MinIO ainda não está pronto... aguardando..."
  sleep 2
  COUNTER=$((COUNTER + 1))
  if [ "$COUNTER" -ge "$MAX_RETRIES" ]; then
    echo "Erro: MinIO não iniciou após várias tentativas."
    exit 1
  fi
done

echo "MinIO iniciado! Configurando MinIO Client..."

# Criar bucket se não existir
if mc ls myminio/testebucket >/dev/null 2>&1; then
  echo "Bucket 'testebucket' já existe."
else
  echo "Criando bucket 'testebucket'..."
  mc mb myminio/testebucket
  if [ $? -eq 0 ]; then
    echo "Bucket 'testebucket' criado com sucesso!"
  else
    echo "Erro ao criar bucket!"
    exit 1
  fi
fi

echo "Configuração do MinIO concluída com sucesso!"
exit 0  # Finaliza o script normalmente
