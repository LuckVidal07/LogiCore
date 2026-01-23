# 📦 LogiCore - Gestão Ativa de Estoque

O **LogiCore** é um ecossistema de gerenciamento de inventário desenvolvido em PHP, utilizando padrões de arquitetura limpa (**Clean Architecture**). O sistema permite o controle total de mercadorias através de duas interfaces distintas (CLI e Web), compartilhando o mesmo núcleo de regras de negócio.

---

## 🚀 Funcionalidades

* **Multi-Interface:** Opere via Terminal (CLI) ou Navegador (Web).
* **Persistência JSON:** Armazenamento leve e rápido com indexação para buscas.
* **Controle de Estoque Crítico:** Alertas visuais automáticos para produtos abaixo do limite (5 unidades).
* **Auditoria de Logs:** Registro de todas as entradas, saídas e exclusões.
* **Dashboard Visual:** Gráficos interativos na interface Web para análise de níveis de estoque usando Chart.js.

## 🛠️ Arquitetura e Padrões

O projeto foi construído focando em desacoplamento e manutenibilidade:
* **Pattern Repository:** Isolamento da lógica de persistência de dados.
* **Service Layer:** Centralização das regras de negócio.
* **PSR-4 Autoloading:** Organização profissional de classes via Composer.
* **Dependency Injection:** Injeção de dependências para facilitar testes e expansões.

## 📁 Estrutura do Projeto

```text
├── bin/                # Ponto de entrada da interface CLI
├── data/               # Armazenamento em formato JSON
├── public/             # Ponto de entrada da interface Web (Front Controller)
├── src/
│   ├── Contract/       # Interfaces e Contratos (Abstração)
│   ├── Entity/         # Classes de Domínio (Produto)
│   ├── Infrastructure/ # Implementação do Repositório (JSON)
│   ├── Service/        # Lógica de Negócio e Auditoria (Logs)
│   └── UI/             # Camadas de Visualização
│       ├── Console/    # Interface Shell do Terminal
│       └── Web/        # Templates HTML/Bootstrap
└── vendor/             # Dependências do Composer
```

## ⚙️ Como Executar
Pré-requisitos
PHP 8.0 ou superior

## 📥 Instalação

Siga os passos abaixo para configurar o projeto em sua máquina local:

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/LuckVidal07/LogiCore.git](https://github.com/LuckVidal07/LogiCore.git)
   ```
2. **Entre na pasta do projeto:**
```bash
cd LogiCore
```
3. **Instale as dependências e gere o autoload:**
```bash
composer install
```

## 💻 Executando o Terminal (CLI)

Para abrir a interface de linha de comando, execute o seguinte comando:
```
php bin/Logicore.php
```
## 🌐 Executando a Interface Web
```bash
php -S localhost:8000 -t public
```

Após iniciar o servidor, acesse:

http://localhost:8000
