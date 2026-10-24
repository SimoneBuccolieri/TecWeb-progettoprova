<?php
class GestioneDati
{
    private $pdo = null;

    private function connessione()
    {
        if ($this->pdo === null) {
            $dsn = getenv('DB_DSN');
            if (!$dsn) {
                $dsn = 'sqlite:' . dirname(__DIR__) . '/storage/passoverde.sqlite';
            }
            $this->pdo = new PDO($dsn, getenv('DB_USER') ?: null, getenv('DB_PASSWORD') ?: null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
                $this->pdo->exec('PRAGMA foreign_keys = ON');
                $this->pdo->exec('PRAGMA busy_timeout = 5000');
            }
        }
        return $this->pdo;
    }

    // Il testo SQL e i valori ricevuti dai moduli restano separati.
    private function esegui($sql, $parametri = [])
    {
        $richiesta = $this->connessione()->prepare($sql);
        $richiesta->execute($parametri);
        return $richiesta;
    }

    public function ultimeEscursioni()
    {
        $richiesta = $this->esegui('SELECT * FROM hikes ORDER BY id LIMIT 3');
        return $richiesta->fetchAll();
    }

    public function tutteEscursioni()
    {
        $richiesta = $this->esegui('SELECT * FROM hikes ORDER BY id DESC');
        return $richiesta->fetchAll();
    }

    public function cercaEscursioni($testo, $difficolta)
    {
        $sql = 'SELECT * FROM hikes';
        $condizioni = [];
        $parametri = [];
        if ($testo !== '') {
            $condizioni[] = "(title LIKE ? ESCAPE '!' OR location LIKE ? ESCAPE '!')";
            // Anche %, _ e ! devono essere cercati come testo.
            $termine = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $testo);
            $termine = '%' . $termine . '%';
            $parametri[] = $termine;
            $parametri[] = $termine;
        }
        if ($difficolta !== '') {
            $condizioni[] = 'difficulty = ?';
            $parametri[] = $difficolta;
        }
        if ($condizioni) {
            $sql .= ' WHERE ' . implode(' AND ', $condizioni);
        }
        $sql .= ' ORDER BY id';
        $richiesta = $this->esegui($sql, $parametri);
        return $richiesta->fetchAll();
    }

    public function trovaEscursione($id)
    {
        $richiesta = $this->esegui('SELECT * FROM hikes WHERE id = ?', [$id]);
        return $richiesta->fetch();
    }

    public function aggiungiEscursione($dati)
    {
        $valori = [
            $dati['title'], $dati['location'], $dati['difficulty'], $dati['distance'],
            $dati['duration'], $dati['elevation'], $dati['description'], $dati['image'],
            date('Y-m-d H:i:s')
        ];
        $this->esegui('INSERT INTO hikes (title,location,difficulty,distance,duration,elevation,description,image,updated_at) VALUES (?,?,?,?,?,?,?,?,?)', $valori);
        return (int)$this->connessione()->lastInsertId();
    }

    public function modificaEscursione($id, $dati)
    {
        $valori = [
            $dati['title'], $dati['location'], $dati['difficulty'], $dati['distance'],
            $dati['duration'], $dati['elevation'], $dati['description'], $dati['image'],
            date('Y-m-d H:i:s'), $id
        ];
        $this->esegui('UPDATE hikes SET title=?,location=?,difficulty=?,distance=?,duration=?,elevation=?,description=?,image=?,updated_at=? WHERE id=?', $valori);
    }

    public function eliminaEscursione($id)
    {
        $this->esegui('DELETE FROM hikes WHERE id=?', [$id]);
    }

    public function trovaUtente($id)
    {
        $richiesta = $this->esegui('SELECT id,username,email,role FROM users WHERE id = ?', [$id]);
        return $richiesta->fetch();
    }

    public function utentePerNome($nome)
    {
        $richiesta = $this->esegui('SELECT * FROM users WHERE username = ?', [$nome]);
        return $richiesta->fetch();
    }

    public function utentePerEmail($email)
    {
        $richiesta = $this->esegui('SELECT id FROM users WHERE email = ?', [$email]);
        return $richiesta->fetch();
    }

    public function aggiungiUtente($nome, $email, $hash, $ruolo = 'user')
    {
        $this->esegui('INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,?)', [$nome, $email, $hash, $ruolo]);
        return (int)$this->connessione()->lastInsertId();
    }

    public function escursioniPreferite($utente)
    {
        $richiesta = $this->esegui('SELECT h.* FROM hikes h JOIN favorites f ON f.hike_id=h.id WHERE f.user_id=? ORDER BY h.title', [$utente]);
        return $richiesta->fetchAll();
    }

    public function preferitoPresente($utente, $escursione)
    {
        $richiesta = $this->esegui('SELECT 1 FROM favorites WHERE user_id = ? AND hike_id = ?', [$utente, $escursione]);
        return (bool)$richiesta->fetchColumn();
    }

    public function aggiungiPreferito($utente, $escursione)
    {
        $this->esegui('INSERT INTO favorites (user_id,hike_id) VALUES (?,?)', [$utente, $escursione]);
    }

    public function rimuoviPreferito($utente, $escursione)
    {
        $this->esegui('DELETE FROM favorites WHERE user_id=? AND hike_id=?', [$utente, $escursione]);
    }

    public function recensioniEscursione($id)
    {
        $richiesta = $this->esegui('SELECT r.*, u.username FROM reviews r JOIN users u ON u.id=r.user_id WHERE hike_id = ? ORDER BY r.updated_at DESC,r.id DESC', [$id]);
        return $richiesta->fetchAll();
    }

    public function recensioniUtente($utente)
    {
        $richiesta = $this->esegui('SELECT r.*,h.title FROM reviews r JOIN hikes h ON h.id=r.hike_id WHERE r.user_id=? ORDER BY r.updated_at DESC', [$utente]);
        return $richiesta->fetchAll();
    }

    public function recensioniDaModerare()
    {
        $richiesta = $this->esegui('SELECT r.*,u.username,h.title FROM reviews r JOIN users u ON u.id=r.user_id JOIN hikes h ON h.id=r.hike_id ORDER BY r.updated_at DESC,r.id DESC');
        return $richiesta->fetchAll();
    }

    public function trovaRecensione($id)
    {
        $richiesta = $this->esegui('SELECT r.*,h.title FROM reviews r JOIN hikes h ON h.id=r.hike_id WHERE r.id=?', [$id]);
        return $richiesta->fetch();
    }

    public function aggiungiRecensione($utente, $escursione, $voto, $testo)
    {
        $this->esegui('INSERT INTO reviews (user_id,hike_id,rating,body,updated_at) VALUES (?,?,?,?,?)', [$utente, $escursione, $voto, $testo, date('Y-m-d H:i:s')]);
    }

    public function modificaRecensione($id, $utente, $voto, $testo)
    {
        $this->esegui('UPDATE reviews SET rating=?, body=?, updated_at=? WHERE id=? AND user_id=?', [$voto, $testo, date('Y-m-d H:i:s'), $id, $utente]);
    }

    public function eliminaRecensione($id)
    {
        $this->esegui('DELETE FROM reviews WHERE id=?', [$id]);
    }

    public function tentativiAccesso($chiave)
    {
        $richiesta = $this->esegui('SELECT * FROM login_attempts WHERE attempt_key = ?', [$chiave]);
        return $richiesta->fetch();
    }

    public function eliminaTentativi($chiave)
    {
        $this->esegui('DELETE FROM login_attempts WHERE attempt_key = ?', [$chiave]);
    }

    public function primoTentativo($chiave)
    {
        $this->esegui('INSERT INTO login_attempts (attempt_key,attempts,last_attempt) VALUES (?,1,?)', [$chiave, time()]);
    }

    public function incrementaTentativi($chiave)
    {
        $this->esegui('UPDATE login_attempts SET attempts = attempts + 1, last_attempt = ? WHERE attempt_key = ?', [time(), $chiave]);
    }

    public function preparaDatabase()
    {
        $driver = $this->connessione()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $schema = 'sqlite.sql';
        if ($driver === 'mysql') {
            $schema = 'mariadb.sql';
        }
        $sql = file_get_contents(dirname(__DIR__) . '/database/' . $schema);
        $this->connessione()->exec($sql);
        return $driver;
    }

    public function contaEscursioni()
    {
        $richiesta = $this->esegui('SELECT COUNT(*) FROM hikes');
        return (int)$richiesta->fetchColumn();
    }

    // Usata dall'esportazione: i nomi delle tabelle non provengono dal visitatore.
    public function datiTabella($tabella)
    {
        if (!in_array($tabella, ['users', 'hikes', 'favorites', 'reviews'], true)) {
            throw new InvalidArgumentException('Tabella non esportabile.');
        }
        $richiesta = $this->esegui('SELECT * FROM ' . $tabella);
        return $richiesta->fetchAll();
    }
}
