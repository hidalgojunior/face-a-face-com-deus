FROM python:3.11-slim

# Set environment variables
ENV PYTHONDONTWRITEBYTECODE 1
ENV PYTHONUNBUFFERED 1

# Install system dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        postgresql-client \
        default-mysql-client \
        git \
        curl \
        build-essential \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Python packages in stages to avoid timeout
RUN pip install --no-cache-dir \
    django \
    djangorestframework \
    flask \
    fastapi \
    uvicorn \
    gunicorn

RUN pip install --no-cache-dir \
    psycopg2-binary \
    pymysql \
    sqlalchemy \
    requests \
    beautifulsoup4

RUN pip install --no-cache-dir \
    pandas \
    numpy \
    matplotlib \
    seaborn

RUN pip install --no-cache-dir \
    jupyter \
    notebook \
    jupyterlab \
    celery \
    redis \
    python-dotenv \
    pillow \
    pytest \
    black \
    flake8

# Install Node.js for JavaScript tools
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Create app directory
WORKDIR /app

# Create projects directory
RUN mkdir -p /app/projects

# Expose ports for development servers
EXPOSE 8000 5000 8888

# Keep container running
CMD ["tail", "-f", "/dev/null"]
