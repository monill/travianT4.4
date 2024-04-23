<?php

class Ranking
{
    public function getUserRank($username)
    {
        $q = "SELECT users.id userid, users.RR totalraid, users.username username, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop
				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY totalpop DESC,totalraid DESC, userid ASC";
        $result = mysql_query($q);
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            $myrank += 1;
            if (strcasecmp($row['username'], $username) == 0) {
                break;
            }
        }
        return $myrank;
    }

    public function getUserAttRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.apall apall
				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY apall DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getUserDefRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.dpall dpall
				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY dpall DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getAllianceRank($id)
    {
        $result = $this->procAllianceRanking(/*$limit*/);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['id'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function procAllianceRanking($limit = "")
    {
        $q = 'SELECT *,(SELECT COUNT(*) FROM users WHERE users.alliance=alidata.id) AS memcount
,(SELECT SUM((SELECT SUM(vdata.pop) FROM vdata WHERE vdata.owner=users.id)) FROM users
WHERE alidata.id=users.alliance) AS totalpop
FROM alidata WHERE 1 ORDER BY totalpop DESC, id ASC ' . $limit;

        return mysql_query($q);
    }

    public function getAllianceAttRank($id)
    {
        $q = "SELECT users.id userid, users.username username, users.alliance alliance, (

				SELECT SUM( alidata.Aap )
				FROM alidata
				WHERE alidata.id = alliance
				)totalpoint

				FROM users
				WHERE users.alliance > 0
				ORDER BY totalpoint DESC, alliance ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['alliance'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getAllianceDefRank($id)
    {
        $q = "SELECT users.id userid, users.username username, users.alliance alliance, (

				SELECT SUM( alidata.Adp )
				FROM alidata
				WHERE alidata.id = alliance
				)totalpoint

				FROM users
				WHERE users.alliance > 0
				ORDER BY totalpoint DESC, alliance ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['alliance'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function procUsersRanking($limit = "")
    {
        $q = "SELECT users.id userid, users.username username,users.alliance alliance, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id !=0 AND id !=2 AND id !=3 AND reg2 != 1
				ORDER BY totalpop DESC,  userid ASC $limit";
        return mysql_query($q);
    }

    public function procUsersAttRanking($limit = "")
    {
        $q = "SELECT users.id userid, users.username username, users.apall apall, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY apall DESC, userid ASC $limit";
        return mysql_query($q);
    }

    public function procUsersDefRanking($limit = "")
    {
        $q = "SELECT users.id userid, users.username username, users.dpall dpall, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY dpall DESC, userid ASC $limit";
        return mysql_query($q);
    }

    public function procAllianceTop10PopRanking($limit = "")
    {
        $q = 'SELECT *,(SELECT COUNT(*) FROM users WHERE users.alliance=alidata.id) AS memcount
,(SELECT SUM((SELECT SUM(vdata.pop) FROM vdata WHERE vdata.owner=users.id)) FROM users
WHERE alidata.id=users.alliance)-alidata.oldrank AS top10popchange
FROM alidata WHERE 1 ORDER BY top10popchange DESC, id ASC ' . $limit;

        return mysql_query($q);
    }

    public function procAllianceAttRanking($limit = "")
    {
        $q = 'SELECT *,(SELECT COUNT(*) FROM users WHERE users.alliance=alidata.id) AS memcount
,(SELECT SUM((SELECT SUM(vdata.pop) FROM vdata WHERE vdata.owner=users.id)) FROM users
WHERE alidata.id=users.alliance) AS totalpop
FROM alidata WHERE 1 ORDER BY Aap DESC, id ASC ' . $limit;
        return mysql_query($q);
    }

    public function procAllianceDefRanking($limit = "")
    {
        $q = 'SELECT *,(SELECT COUNT(*) FROM users WHERE users.alliance=alidata.id) AS memcount
,(SELECT SUM((SELECT SUM(vdata.pop) FROM vdata WHERE vdata.owner=users.id)) FROM users
WHERE alidata.id=users.alliance) AS totalpop
FROM alidata WHERE 1 ORDER BY Adp DESC, id ASC ' . $limit;
        return mysql_query($q);
    }

    public function getATop10AttRank($id)
    {
        $q = "SELECT alidata.id allyid, alidata.ap ap
				FROM alidata
				WHERE alidata.id > 0
				ORDER BY ap DESC, allyid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['allyid'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getATop10DefRank($id)
    {
        $q = "SELECT alidata.id allyid, alidata.dp dp
				FROM alidata
				WHERE alidata.id > 0
				ORDER BY dp DESC, allyid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['allyid'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getATop10ClpRank($id)
    {
        $q = "SELECT alidata.id allyid, alidata.clp clp
				FROM alidata
				WHERE alidata.id > 0
				ORDER BY clp DESC, allyid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['allyid'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getATop10RobbersRank($id)
    {
        $q = "SELECT alidata.id allyid, alidata.RR RR
				FROM alidata
				WHERE alidata.id > 0
				ORDER BY RR DESC, allyid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['allyid'] == $id) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getVillageRank($wid)
    {
        $q = "SELECT `wref` FROM vdata WHERE wref != 0 AND owner != 2 ORDER BY pop DESC, name ASC, lastupdate DESC";
        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($row['wref'] == $wid) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function procVillagesRanking($limit = "")
    {
        $q = "SELECT * FROM vdata WHERE wref != 0 AND owner != 2 ORDER BY pop DESC, name ASC, lastupdate DESC $limit";
        return mysql_query($q);
    }

    public function getTop10AttRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.ap ap, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY ap DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getTop10DefRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.dp dp, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY dp DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function getTop10ClpRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.clp clp, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY totalpop DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }

    public function procUsersTop10PopRank($limit = '')
    {
        $q = "SELECT users.id userid, users.username username,
				((SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)-oldrank)top10popchange

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY top10popchange DESC, userid ASC " . $limit;

        return mysql_query($q);
    }

    public function getTop10RobbersRank($username)
    {
        $q = "SELECT users.id userid, users.username username, users.RR RR, (
				SELECT SUM( vdata.pop )
				FROM vdata
				WHERE vdata.owner = userid
				)totalpop, (

				SELECT COUNT( vdata.wref )
				FROM vdata
				WHERE vdata.owner = userid AND type != 99
				)totalvillages

				FROM users
				WHERE users.id > 3 AND reg2 != 1
				ORDER BY RR DESC, userid ASC";

        $result = mysql_query($q);
        $i = 1;
        $myrank = 0;
        while ($row = mysql_fetch_array($result)) {
            if (strcasecmp($row['username'], $username) == 0) {
                $myrank = $i;
            }
            $i++;
        }
        return $myrank;
    }
}

$ranking = new Ranking;
