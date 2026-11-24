#!/bin/zsh
set -e
SCRIPT_DIR=${0:A:h}
PROJECT_DIR=${SCRIPT_DIR:h}
cd "$PROJECT_DIR"

if [[ ! -d node_modules ]]; then
  echo "Preparando o ambiente local pela primeira vez..."
  npm install
fi

echo "Iniciando o Portal Expresso RN em http://127.0.0.1:8080"
echo "Para encerrar, pressione Control + C."
npm run start
