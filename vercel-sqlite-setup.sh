#!/bin/bash

# Crear directorio temporal para SQLite
mkdir -p /tmp

# Copiar base de datos SQLite
cp database/database.sqlite /tmp/database.sqlite

# Asignar permisos
chmod 777 /tmp/database.sqlite

echo "Base de datos copiada a directorio temporal"
