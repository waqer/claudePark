FROM node:22-alpine

WORKDIR /app

# Install dependencies first (better layer caching)
COPY package*.json ./
RUN npm install

# Copy the rest of the source
COPY . .

EXPOSE 5173

# Call vite directly from node_modules
CMD ["node_modules/.bin/vite", "--host", "0.0.0.0"]