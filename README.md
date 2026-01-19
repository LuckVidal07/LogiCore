# 📦 LogiCore - Sistema de Gestão de Estoque

O **LogiCore** é uma aplicação de terminal (CLI) desenvolvida em PHP moderno para demonstrar o uso de arquitetura de software avançada, focando em manutenibilidade, testabilidade e separação de responsabilidades.

## 🛠️ Tecnologias e Padrões Utilizados

Este projeto não utiliza frameworks, sendo construído do zero para aplicar:

* **PHP 8.x + Composer** (Autoloading PSR-4)
* **Domain-Driven Design (DDD) Lite**: Uso de Entidades para encapsular regras de negócio.
* **Repository Pattern**: Abstração da camada de persistência de dados (JSON).
* **Dependency Injection**: Injeção de dependências via construtor para baixo acoplamento.
* **Service Layer**: Camada de serviço para coordenação das operações.
* **Logging Service**: Auditoria de todas as movimentações do sistema.

## 🏗️ Arquitetura do Projeto

A estrutura segue os princípios do **SOLID**, dividida em:

1.  **Entity**: Objeto de domínio `Produto` que garante a integridade dos dados.
2.  **Contract**: Interfaces que definem os contratos dos repositórios.
3.  **Infrastructure**: Implementação concreta da persistência (JSON).
4.  **Service**: Lógica de negócio (baixas, reposição, alertas críticos).



## 🚀 Como Executar

1. Clone o repositório.
2. Execute `composer install` para gerar o autoloader.
3. Inicie a aplicação com:
   ```bash
   php public/index.php
