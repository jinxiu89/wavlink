tadata()['ContentType'];
    }

    protected function getSourceSize()
    {
        return $this->getSourceMetadata()['ContentLength'];
    }

    private function getSourceMetadata()
    {
        if (empty($this->sourceMetadata)) {
            $this->sourceMetadata = $this->fetchSourceMetadata();
        }

        return $this->sourceMetadata;
    }

    private function fetchSourceMetadata()
    {
        if ($this->config['source_metadata'] instanceof ResultInterface) {
            return $this->config['source_metadata'];
        }

        list($bucket, $key) = explode('/', ltrim($this->source, '/'), 2);
        $headParams = [
            'Bucket' => $bucket,
            'Key' => $key,
        ];
        if (strpos($key, '?')) {
            list($key, $query) = explode('?', $key, 2);
            $headParams['Key'] = $key;
            $query = Psr7\Query::parse($query, false);
            if (isset($query['versionId'])) {
                $headParams['VersionId'] = $query['versionId'];
            }
        }
        return $this->client->headObject($headParams);
    }
}
