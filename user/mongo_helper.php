<?php
// MongoDB Helper Functions using MongoDB\Driver\Manager
// Database: support_db, Collection: tickets

function mongoInsertOne($manager, $document) {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert($document);
    $namespace = 'support_db.tickets';
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $manager->executeBulkWrite($namespace, $bulk, $writeConcern);
    return $result->getInsertedCount();
}

function mongoFindOne($manager, $filter) {
    $query = new MongoDB\Driver\Query($filter);
    $namespace = 'support_db.tickets';
    $cursor = $manager->executeQuery($namespace, $query);
    $results = $cursor->toArray();
    if (count($results) > 0) {
        // Convert BSON document to PHP array
        $doc = $results[0];
        $result = json_decode(json_encode($doc), true);
        // Fix _id field if it's an array with $oid
        if (isset($result['_id']) && is_array($result['_id']) && isset($result['_id']['$oid'])) {
            $result['_id'] = $result['_id']['$oid'];
        }
        return $result;
    }
    return null;
}

function mongoFind($manager, $filter = []) {
    $query = new MongoDB\Driver\Query($filter);
    $namespace = 'support_db.tickets';
    $cursor = $manager->executeQuery($namespace, $query);
    $results = [];
    foreach ($cursor as $doc) {
        // Convert BSON document to PHP array
        $result = json_decode(json_encode($doc), true);
        // Fix _id field if it's an array with $oid
        if (isset($result['_id']) && is_array($result['_id']) && isset($result['_id']['$oid'])) {
            $result['_id'] = $result['_id']['$oid'];
        }
        $results[] = $result;
    }
    return $results;
}

function mongoUpdateOne($manager, $filter, $update) {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->update($filter, $update);
    $namespace = 'support_db.tickets';
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $manager->executeBulkWrite($namespace, $bulk, $writeConcern);
    return $result->getModifiedCount();
}

function mongoDistinct($manager, $field) {
    $command = new MongoDB\Driver\Command([
        'distinct' => 'tickets',
        'key' => $field
    ]);
    $namespace = 'support_db';
    $cursor = $manager->executeCommand($namespace, $command);
    $result = current($cursor->toArray());
    return isset($result->values) ? $result->values : [];
}
?>

