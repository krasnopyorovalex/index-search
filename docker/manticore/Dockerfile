FROM manticoresearch/manticore:29.0.2

USER root

# Устанавливаем языковые пакеты для русской лемматизации
RUN apt-get update && \
    apt-get install -y --no-install-recommends manticore-language-packs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Создаём каталог для binlog и передаём его пользователю manticore
RUN mkdir -p /var/lib/manticore/binlog && \
    chown -R manticore:manticore /var/lib/manticore

USER manticore
