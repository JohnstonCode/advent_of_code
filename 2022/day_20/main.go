package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

type node struct {
	num  int
	prev *node
	next *node
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	var nums []*node
	var num0 *node

	for _, s := range strings.Split(string(content), "\n") {
		n, _ := strconv.Atoi(strings.TrimSpace(s))
		num := &node{num: n}
		nums = append(nums, num)

		if n == 0 {
			num0 = num
		}
	}

	setupLinks(nums)
	mix(nums)

	fmt.Printf("Part 1: %v\n", sum(num0))

	decKey := 811589153

	for _, n := range nums {
		n.num *= decKey
	}

	setupLinks(nums)

	for i := 0; i < 10; i++ {
		mix(nums)
	}

	fmt.Printf("Part 2: %v\n", sum(num0))

}

func setupLinks(nums []*node) {
	l := len(nums)
	nums[0].prev = nums[l-1]
	nums[l-1].next = nums[0]

	for i := 1; i < l; i++ {
		nums[i].prev = nums[i-1]
		nums[i-1].next = nums[i]
	}
}

func mix(numbs []*node) {
	l := len(numbs)
	for _, n := range numbs {
		p := n.prev
		n.prev.next = n.next
		n.next.prev = n.prev

		p = move(p, n.num%(l-1))
		n.prev = p
		n.next = p.next
		n.prev.next = n
		n.next.prev = n
	}
}

func move(n *node, i int) *node {
	for i < 0 {
		n = n.prev
		i++
	}

	for i > 0 {
		n = n.next
		i--
	}

	return n
}

func sum(num0 *node) int {
	s := 0
	for i, n := 0, num0; i < 3; i++ {
		n = move(n, 1000)
		s += n.num
	}

	return s
}
