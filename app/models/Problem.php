<?php
    class Problem{
        public function __construct(){
            $this->db = new Database();
        }

        public function create($data){
            $this->db->query("INSERT into problem(name,author,examid,description,inputcase,outputcase,marks) values(:name,:author,:examid,:description,:inputcase,:outputcase,:marks)");
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':author',$data['author']);
            $this->db->bind(':examid',$data['examId']);
            $this->db->bind(':description',$data['description']);
            $this->db->bind(':inputcase',$data['input']);
            $this->db->bind(':outputcase',$data['output']);
            $this->db->bind(':marks', $data['marks']);
            $this->db->execute();
        }

        //Fetch single proble with problem id
        public function show($id){
            $this->db->query("SELECT problem.marks,problem.id,problem.name,problem.examid,user.name as author,problem.description,user.id as userid from problem join user on problem.author = user.id where  problem.id=:id");
            $this->db->bind(':id',$id);

            $data = $this->db->single();
            return $data;
        }

        //Fetch all problems
        public function all(){
            $now = date("Y-m-d H:i:s");
            $this->db->query("SELECT problem.id as id, problem.name as name,problem.author as author from problem join exam on problem.examid = exam.id where exam.begin_time <= :date");
            $this->db->bind(":date",$now);
            $data = $this->db->resultSet();
            return $data;
        }

        //Fetch Test Case
        public function testCase($id){
            $this->db->query('SELECT inputcase,outputcase from problem where id=:id');
            $this->db->bind(':id',$id);
            $data = $this->db->single();
            return $data;
        }

        //Store code
        public function pushCode($data){
            $this->db->query('INSERT into submission(problemId,examId,res,code,userId,language) values(:problemId,:examId,:res,:code,:userId,:language)');

            $this->db->bind(':problemId',$data['problemId']);
            $this->db->bind(':examId',$data['examId']);
            $this->db->bind(':res',$data['res']);
            $this->db->bind(':code',$data['code']);
            $this->db->bind(':userId',$data['userId']);
            $this->db->bind(':language',$data['language']);
            
            $this->db->execute();
        }

        //fetch all submission for one particular problem
        public function oneSubmission($problemId,$userId){
            $this->db->query('SELECT submission.problemId,submission.res,submission.language,submission.date,problem.name FROM submission join problem where submission.problemId=:problemId and submission.userId = :userId and problem.id=:pid ORDER BY submission.date DESC');
            $this->db->bind('problemId',$problemId);
            $this->db->bind('userId',$userId);
            $this->db->bind('pid',$problemId);

            $data = $this->db->resultSet();
            return $data;
        }

        //get exam details by id
        public function getExam($id,$author){
            $this->db->query('SELECT * from exam where id=:id and author=:author');
            $this->db->bind(":id",$id);
            $this->db->bind(":author",$author);

            $data = $this->db->resultSet();
            return $data;
        }

        //problem details
        public function details($id){
            $this->db->query('SELECT * from problem where id=:id');
            $this->db->bind(':id',$id);
            $data = $this->db->single();
            return $data;
        }

        //update problem
        public function update($id,$data){
            $this->db->query('UPDATE problem SET name=:name,description=:description,inputcase=:input,outputcase=:output,marks=:marks WHERE id=:id');
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':description',$data['description']);
            $this->db->bind(':input',$data['input']);
            $this->db->bind(':output',$data['output']);
            $this->db->bind(':id',$id);
            $this->db->bind(':marks',$data['marks']);

            $this->db->execute();
        }

        //remove problem
        public function delete($id){
            $this->db->query('DELETE from problem where id=:id');
            $this->db->bind(':id',$id);
            $this->db->execute();
        }

        //fetch Last submitted code
        public function lastSubmitCode($pid, $userId){
            $this->db->query('SELECT * from submission where problemId=:pid and userId=:userId order by date desc');
            $this->db->bind(':pid',$pid);
            $this->db->bind(':userId',$userId);
            
            $data = $this->db->single();
            return $data;
        }
    }